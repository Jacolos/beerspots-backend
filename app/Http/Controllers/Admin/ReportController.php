<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        // Calculate statistics
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];

        // Build the reports query
        $reports = Report::with(['user', 'beerSpot'])
            ->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $term = '%' . $request->search . '%';
                    $q->whereHas('user', function($subQ) use ($term) {
                        $subQ->where('name', 'like', $term)
                             ->orWhere('email', 'like', $term);
                    })
                    ->orWhereHas('beerSpot', function($subQ) use ($term) {
                        $subQ->where('name', 'like', $term);
                    })
                    ->orWhere('description', 'like', $term);
                });
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->reason, function($query, $reason) {
                $query->where('reason', $reason);
            })
            ->when($request->sort, function($query, $sort) {
                if ($sort === 'oldest') {
                    $query->oldest();
                } else {
                    $query->latest();
                }
            }, function($query) {
                $query->latest();
            })
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact('reports', 'stats'));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $report->load([
            'user', 
            'beerSpot',
            'moderator' => function($query) {
                $query->select('id', 'name', 'email');
            }
        ]);

        // Get related reports for the same beer spot
        $relatedReports = Report::where('beer_spot_id', $report->beer_spot_id)
            ->where('id', '!=', $report->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.reports.show', compact('report', 'relatedReports'));
    }

    /**
     * Update the specified report.
     */
public function update(Request $request, Report $report)
{
    $validated = $request->validate([
        'status' => 'required|in:resolved,rejected',
        'admin_notes' => 'nullable|string|max:1000',
    ]);

    try {
        $report->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'moderated_at' => now(),
            'moderated_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', __('reports.messages.status_updated'));

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', __('reports.messages.update_failed'));
    }
}

    /**
     * Remove the specified report.
     */
    public function destroy(Report $report)
    {
        try {
            if ($report->status === 'pending') {
                return redirect()
                    ->back()
                    ->with('error', __('reports.messages.cannot_delete_pending'));
            }

            $report->delete();

            return redirect()
                ->route('admin.reports.index')
                ->with('success', __('reports.messages.deleted'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', __('reports.messages.delete_failed'));
        }
    }

    /**
     * Bulk update reports status.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reports,id',
            'status' => 'required|in:resolved,rejected'
        ]);

        try {
            DB::beginTransaction();

            $reports = Report::whereIn('id', $validated['ids'])
                ->where('status', 'pending')
                ->get();

            foreach ($reports as $report) {
                $report->update([
                    'status' => $validated['status'],
                    'moderated_at' => now(),
                    'moderated_by' => auth()->id(),
                ]);

                if ($validated['status'] === 'resolved') {
                    $this->handleReportResolution($report);
                }
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', __('reports.messages.bulk_updated'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', __('reports.messages.bulk_update_failed'));
        }
    }

    /**
     * Handle actions needed when resolving a report.
     */
    private function handleReportResolution(Report $report)
    {
        // Add your resolution logic here
        // For example:
        
        // 1. Update beer spot status if multiple reports
        $reportsCount = Report::where('beer_spot_id', $report->beer_spot_id)
            ->where('status', 'resolved')
            ->count();

        if ($reportsCount >= 3) {
            $report->beerSpot->update([
                'status' => 'pending_review'
            ]);
        }

        // 2. Notify the reporter
        //$report->user->notify(new ReportResolvedNotification($report));

        // 3. Notify beer spot owner if exists
        if ($report->beerSpot->user) {
        //    $report->beerSpot->user->notify(new BeerSpotReportedNotification($report));
        }
    }

    /**
     * Get report statistics for dashboard.
     */
    public function getStatistics()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth();

        return [
            'total' => [
                'all' => Report::count(),
                'this_month' => Report::where('created_at', '>=', $startOfMonth)->count(),
                'change' => $this->calculatePercentageChange(
                    Report::whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])->count(),
                    Report::where('created_at', '>=', $startOfMonth)->count()
                )
            ],
            'by_status' => [
                'pending' => Report::where('status', 'pending')->count(),
                'resolved' => Report::where('status', 'resolved')->count(),
                'rejected' => Report::where('status', 'rejected')->count(),
            ],
            'by_reason' => [
                'inappropriate' => Report::where('reason', 'inappropriate')->count(),
                'spam' => Report::where('reason', 'spam')->count(),
                'outdated' => Report::where('reason', 'outdated')->count(),
            ],
            'response_time' => $this->calculateAverageResponseTime()
        ];
    }

    /**
     * Calculate percentage change between two values.
     */
    private function calculatePercentageChange($old, $new)
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return round((($new - $old) / $old) * 100, 1);
    }

    /**
     * Calculate average response time for reports.
     */
    private function calculateAverageResponseTime()
    {
        return Report::whereNotNull('moderated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, moderated_at)) as avg_hours')
            ->first()
            ->avg_hours ?? 0;
    }
private function checkSpotReports(int $spotId): void
{
    $recentReportsCount = Report::where('beer_spot_id', $spotId)
        ->where('created_at', '>=', now()->subDays(7))
        ->count();

    if ($recentReportsCount >= 3) {
        DB::table('beer_spots')
            ->where('id', $spotId)
            ->update([
                'flagged_for_review' => true,
                'flagged_at' => now(),
                'flagged_by' => auth()->id(),
                'updated_at' => now()
            ]);
    }
}

}