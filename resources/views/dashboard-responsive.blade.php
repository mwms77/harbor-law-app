{{-- resources/views/admin/dashboard.blade.php --}}
{{-- RESPONSIVE ADMIN DASHBOARD --}}

@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- Page Header --}}
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                Dashboard
            </h1>
            <p class="mt-1 text-sm sm:text-base text-gray-600">
                Welcome back! Here's what's happening with your practice.
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        {{-- Stats Grid - 1 col mobile, 2 col tablet, 4 col desktop --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            
            {{-- Total Users --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-blue-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Users
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        {{ $totalUsers ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.users.index') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            View all
                        </a>
                    </div>
                </div>
            </div>

            {{-- Active Clients --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-green-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Active Clients
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        {{ $activeClients ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.users.index', ['filter' => 'clients']) }}" class="font-medium text-green-600 hover:text-green-500">
                            View all
                        </a>
                    </div>
                </div>
            </div>

            {{-- Total Documents --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-purple-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Documents
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        {{ $totalDocuments ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.uploads') }}" class="font-medium text-purple-600 hover:text-purple-500">
                            View all
                        </a>
                    </div>
                </div>
            </div>

            {{-- Pending Intakes --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-yellow-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Pending Intakes
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">
                                        {{ $pendingIntakes ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.users.index') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                            View all
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Two Column Layout - Stack on mobile, side-by-side on desktop --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            {{-- Recent Users --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Recent Users
                    </h2>
                    
                    @if(isset($recentUsers) && $recentUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3 min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        View
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-8">
                            No recent users
                        </p>
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View all users â†’
                    </a>
                </div>
            </div>

            {{-- Recent Documents --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Recent Documents
                    </h2>
                    
                    @if(isset($recentDocuments) && $recentDocuments->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentDocuments as $doc)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $doc->document_name }}
                                    </p>
                                    <div class="flex items-center mt-1 text-xs text-gray-500">
                                        <span class="truncate">{{ $doc->user->first_name }} {{ $doc->user->last_name }}</span>
                                        <span class="mx-2">â€¢</span>
                                        <span>{{ $doc->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $doc->status === 'executed' ? 'bg-green-100 text-green-800' : 
                                           ($doc->status === 'final' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-8">
                            No recent documents
                        </p>
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    <a href="{{ route('admin.uploads') }}" 
                       class="text-sm font-medium text-purple-600 hover:text-purple-500">
                        View all documents â†’
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Activity - Full width --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Recent Activity
                </h2>
                
                @if(isset($recentActivity) && $recentActivity->count() > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($recentActivity as $index => $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    {{ $activity->description ?? 'Activity' }}
                                                </p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-8">
                        No recent activity
                    </p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
