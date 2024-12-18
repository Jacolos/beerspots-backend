@extends('layouts.admin')

@section('title', __('reports.title.show'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('reports.actions.back_to_list') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('reports.title.show') }} #{{ $report->id }}</h1>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-semibold
            {{ $report->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800' : '' }}
            {{ $report->status === 'resolved' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800' : '' }}
            {{ $report->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800' : '' }}">
            {{ __("reports.statuses.$report->status") }}
        </span>
    </div>

    <div class="grid gap-6">
        <!-- Report Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('reports.details.report') }}</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.created_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->created_at->format('d.m.Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.status') }}</dt>
                    <dd class="mt-1">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            {{ $report->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                            {{ $report->status === 'resolved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                            {{ $report->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                            {{ __("reports.statuses.$report->status") }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.reason') }}</dt>
                    <dd class="mt-1">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            {{ $report->reason === 'inappropriate' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}
                            {{ $report->reason === 'spam' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300' : '' }}
                            {{ $report->reason === 'outdated' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                            {{ $report->reason === 'wrong_location' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' : '' }}
                            {{ $report->reason === 'closed' ? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300' : '' }}
                            {{ $report->reason === 'duplicate' ? 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-800 dark:text-cyan-300' : '' }}
                            {{ $report->reason === 'incorrect_info' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                            {{ $report->reason === 'other' ? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300' : '' }}">
                            {{ __("reports.reasons.$report->reason") }}
                        </span>
                    </dd>
                </div>

                @if($report->status !== 'pending')
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.moderated_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->moderated_at->format('d.m.Y H:i') }}</dd>
                    </div>
                @endif
            </dl>

            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.description') }}</dt>
                <dd class="mt-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                    {{ $report->description }}
                </dd>
            </div>
        </div>

        <!-- Reporter Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('reports.details.reporter') }}</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.user') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->user->email }}</dd>
                </div>
            </dl>
        </div>

        <!-- Reported Beer Spot -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('reports.details.beer_spot') }}</h2>
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.beer_spot') }}</dt>
                    <dd class="mt-1">
                        <a href="{{ route('admin.beer-spots.show', $report->beerSpot) }}" 
                           class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                            {{ $report->beerSpot->name }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Adres</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->beerSpot->address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status miejsca</dt>
                    <dd class="mt-1">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            {{ $report->beerSpot->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                            {{ $report->beerSpot->status === 'inactive' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}
                            {{ $report->beerSpot->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}">
                            {{ $report->beerSpot->status }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        @if($report->status === 'pending')
            <!-- Moderation Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('reports.details.moderation') }}</h2>
                
                <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('reports.fields.admin_notes') }}
                        </label>
                        <textarea id="admin_notes"
                                  name="admin_notes" 
                                  rows="3" 
                                  class="mt-1 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500"
                                  placeholder="Dodaj notatkę dotyczącą podjętej decyzji...">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="submit" 
                                name="status" 
                                value="rejected"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            {{ __('reports.actions.reject') }}
                        </button>
                        <button type="submit" 
                                name="status" 
                                value="resolved"
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                            {{ __('reports.actions.resolve') }}
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Moderation Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('reports.details.moderation') }}</h2>
                <dl class="space-y-4">
                    @if($report->moderator)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.moderated_by') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->moderator->name }}</dd>
                        </div>
                    @endif

                    @if($report->admin_notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('reports.fields.admin_notes') }}</dt>
                            <dd class="mt-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                                {{ $report->admin_notes }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        @endif

        <!-- Delete Action -->
        @if($report->status !== 'pending')
            <div class="flex justify-end">
                <form action="{{ route('admin.reports.destroy', $report) }}" 
                      method="POST" 
                      onsubmit="return confirm('{{ __('reports.actions.confirm_delete') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        {{ __('reports.actions.delete') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection