{{-- resources/views/admin/users/index.blade.php --}}
{{-- RESPONSIVE USER LIST --}}

@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- Page Header --}}
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Users
                    </h1>
                    <p class="mt-1 text-sm sm:text-base text-gray-600">
                        Manage all users and clients
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('admin.users.create') }}" 
                       class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add User
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        {{-- Filters and Search - Stack on mobile, row on desktop --}}
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    {{-- Search --}}
                    <div class="flex-1 max-w-lg">
                        <label for="search" class="sr-only">Search users</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="search" 
                                   name="search" 
                                   id="search" 
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                   placeholder="Search by name or email...">
                        </div>
                    </div>
                    
                    {{-- Filter Buttons --}}
                    <div class="flex flex-wrap gap-2">
                        <button type="button" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            All Users
                        </button>
                        <button type="button" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Clients Only
                        </button>
                        <button type="button" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Leads Only
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users List --}}
        @if(isset($users) && $users->count() > 0)
            
            {{-- MOBILE VIEW: Cards (< lg) --}}
            <div class="lg:hidden space-y-4">
                @foreach($users as $user)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4">
                        {{-- User Info --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center">
                                        <span class="text-lg font-medium text-white">
                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <p class="text-base font-medium text-gray-900 truncate">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                @if($user->is_client)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Client
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Lead
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Stats --}}
                        <div class="grid grid-cols-3 gap-3 mb-3 py-3 border-t border-b border-gray-200">
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Documents</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->estatePlans->count() }}</p>
                            </div>
                            <div class="text-center border-l border-r border-gray-200">
                                <p class="text-xs text-gray-500">Intakes</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->intakeSubmissions->count() }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Joined</p>
                                <p class="text-xs font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- DESKTOP VIEW: Table (≥ lg) --}}
            <div class="hidden lg:block bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Documents
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Intakes
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_client)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Client
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Lead
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->estatePlans->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->intakeSubmissions->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    View
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination - Responsive --}}
            <div class="mt-6">
                {{ $users->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-lg shadow">
                <div class="text-center py-12 px-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding a new user.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add User
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// Simple search functionality
document.getElementById('search')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const userCards = document.querySelectorAll('[data-user-card]');
    const userRows = document.querySelectorAll('[data-user-row]');
    
    [...userCards, ...userRows].forEach(element => {
        const text = element.textContent.toLowerCase();
        element.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endpush
