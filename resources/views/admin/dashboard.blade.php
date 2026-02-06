@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-2 text-gray-600">Estate planning application overview and statistics</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        {{-- Total Users --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed Intakes --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed Intakes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_intakes'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Users with Uploads --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Users with Documents</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['users_with_uploads'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Reviews --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_reviews'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Uploads This Week --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Uploads This Week</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['uploads_this_week'] }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.users') }}" 
           class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg shadow-md p-6 hover:from-purple-700 hover:to-indigo-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Manage Users</h3>
                    <p class="text-purple-100 text-sm mt-1">View and manage all clients</p>
                </div>
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.uploads') }}" 
           class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg shadow-md p-6 hover:from-blue-700 hover:to-cyan-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Client Documents</h3>
                    <p class="text-blue-100 text-sm mt-1">View all uploaded documents</p>
                </div>
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.users', ['status' => 'in_progress']) }}" 
           class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg shadow-md p-6 hover:from-yellow-600 hover:to-orange-600 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Pending Reviews</h3>
                    <p class="text-yellow-100 text-sm mt-1">Cases requiring attention</p>
                </div>
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>
    </div>

    {{-- Recent Users Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Recent Users</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intake</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploads</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentUsers as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status_color }}">
                                    {{ $user->status_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->hasCompletedIntake())
                                    <span class="text-green-600 font-medium">✓ Complete</span>
                                @else
                                    <span class="text-gray-400">Not started</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->upload_badge_color }}">
                                    {{ $user->uploads->count() }} files
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-purple-600 hover:text-purple-900">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No users registered yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
