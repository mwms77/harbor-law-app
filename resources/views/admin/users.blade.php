@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
            <p class="mt-2 text-gray-600">View and manage all client accounts</p>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Name or email..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Intake Completion Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Intake Completed</label>
                <select name="intake_completed" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All</option>
                    <option value="yes" {{ request('intake_completed') == 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ request('intake_completed') == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            {{-- Has Uploads Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Has Uploads</label>
                <select name="has_uploads" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All</option>
                    <option value="yes" {{ request('has_uploads') == 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ request('has_uploads') == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            {{-- Filter Buttons --}}
            <div class="md:col-span-4 flex space-x-4">
                <button type="submit" 
                        class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    Apply Filters
                </button>
                <a href="{{ route('admin.users') }}" 
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                    @forelse($users as $user)
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
                                    <div class="flex items-center text-green-600">
                                        <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-medium">Complete</span>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $user->intake_completed_at->format('M j, Y') }}</div>
                                @else
                                    <span class="text-sm text-gray-400">Not started</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->upload_badge_color }}">
                                    {{ $user->uploads->count() }} {{ $user->uploads->count() == 1 ? 'file' : 'files' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-purple-600 hover:text-purple-900">View</a>
                                @if($user->uploads->count() > 0)
                                    <a href="{{ route('admin.uploads.user', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900">Documents</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="text-gray-500">
                                    @if(request()->hasAny(['search', 'status', 'intake_completed', 'has_uploads']))
                                        No users found matching your filters
                                    @else
                                        No users registered yet
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
