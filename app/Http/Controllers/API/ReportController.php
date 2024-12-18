<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\BeerSpot;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(BeerSpot $beerSpot)
    {
        $reports = $beerSpot->reports()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $reports
        ]);
    }

    public function store(Request $request)
    {

    try {
        $validated = $request->validate([
            'spot_id' => 'required|exists:beer_spots,id',
            'reason' => 'required|string|max:255',
            'description' => 'required|string'
        ]);


            DB::beginTransaction();

            $report = Report::create([
                'user_id' => auth()->id(),
                'beer_spot_id' => $validated['spot_id'],
                'reason' => $validated['reason'],
                'description' => $validated['description'],
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Automatyczne flagowanie miejsca jeśli ma wiele zgłoszeń
            $this->checkSpotReports($report->beer_spot_id);

            DB::commit();

            return response()->json([
                'message' => 'Zgłoszenie zostało wysłane pomyślnie',
                'data' => $report
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Report creation error:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'spot_id' => $request->spot_id
            ]);

            return response()->json([
                'message' => 'Wystąpił błąd podczas wysyłania zgłoszenia',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
    public function show(Report $report)
    {
        return response()->json([
            'data' => $report->load('user:id,name', 'beerSpot')
        ]);
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,resolved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        try {
            $report->update($validated);

            return response()->json([
                'message' => 'Status zgłoszenia został zaktualizowany',
                'data' => $report
            ]);
        } catch (\Exception $e) {
            Log::error('Report update error:', [
                'error' => $e->getMessage(),
                'report_id' => $report->id,
                'data' => $validated
            ]);

            return response()->json([
                'message' => 'Wystąpił błąd podczas aktualizacji zgłoszenia'
            ], 500);
        }
    }

    public function destroy(Report $report)
    {
        try {
            $report->delete();
            return response()->json([
                'message' => 'Zgłoszenie zostało usunięte'
            ]);
        } catch (\Exception $e) {
            Log::error('Report deletion error:', [
                'error' => $e->getMessage(),
                'report_id' => $report->id
            ]);

            return response()->json([
                'message' => 'Wystąpił błąd podczas usuwania zgłoszenia'
            ], 500);
        }
    }
}