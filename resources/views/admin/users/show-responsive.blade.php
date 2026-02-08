{{-- resources/views/admin/users/show.blade.php --}}
{{-- RESPONSIVE VERSION - Works beautifully on mobile, tablet, and desktop --}}

@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- Page Header - Responsive --}}
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- User name and back button --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
                
                {{-- Action buttons - Stack on mobile, row on desktop --}}
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </a>
                    <button type="button"
                            class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        {{-- Grid Layout: 1 column mobile, 3 columns desktop --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Sidebar - Full width on mobile, 1/3 on desktop --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- User Information Card --}}
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            User Information
                        </h2>
                        
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $user->email }}
                                    </a>
                                </dd>
                            </div>
                            
                            @if($user->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="tel:{{ $user->phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $user->phone }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Client Status</dt>
                                <dd class="mt-1">
                                    @if($user->is_client)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active Client
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Lead
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->created_at->format('M d, Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                {{-- Quick Stats Card --}}
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Quick Stats
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Estate Plans</span>
                                <span class="text-2xl font-bold text-gray-900">
                                    {{ $user->estatePlans->count() }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Intake Forms</span>
                                <span class="text-2xl font-bold text-gray-900">
                                    {{ $user->intakeSubmissions->count() }}
                                </span>
                            </div>
                            
                            @if($user->estatePlans->where('status', 'executed')->count() > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Executed Docs</span>
                                <span class="text-2xl font-bold text-green-600">
                                    {{ $user->estatePlans->where('status', 'executed')->count() }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
            
            {{-- Main Content Area - Full width on mobile, 2/3 on desktop --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Estate Plans Section --}}
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3 sm:mb-0">
                                Estate Plans
                            </h2>
                            <button type="button" 
                                    onclick="document.getElementById('upload-modal').classList.remove('hidden')"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Upload Document
                            </button>
                        </div>
                        
                        @if($user->estatePlans->count() > 0)
                            {{-- MOBILE VIEW: Cards (visible only on small screens) --}}
                            <div class="space-y-4 lg:hidden">
                                @foreach($user->estatePlans as $plan)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                    {{-- Document name --}}
                                    <h3 class="font-medium text-gray-900 mb-2">
                                        {{ $plan->document_name }}
                                    </h3>
                                    
                                    {{-- Status and date row --}}
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $plan->status === 'executed' ? 'bg-green-100 text-green-800' : 
                                               ($plan->status === 'final' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($plan->status) }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $plan->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    
                                    @if($plan->executed_date)
                                    <div class="text-xs text-gray-600 mb-3">
                                        <span class="font-medium">Executed:</span> {{ $plan->executed_date->format('M d, Y') }}
                                    </div>
                                    @endif
                                    
                                    {{-- Action buttons --}}
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('admin.estate-plans.view', $plan) }}" 
                                           class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.estate-plans.download', $plan) }}" 
                                           class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            {{-- DESKTOP VIEW: Table (hidden on small screens) --}}
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Document Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Executed Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Uploaded
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->estatePlans as $plan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $plan->document_name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $plan->status === 'executed' ? 'bg-green-100 text-green-800' : 
                                                       ($plan->status === 'final' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($plan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $plan->executed_date ? $plan->executed_date->format('M d, Y') : '—' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $plan->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.estate-plans.view', $plan) }}" 
                                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.estate-plans.download', $plan) }}" 
                                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                                    Download
                                                </a>
                                                <form action="{{ route('admin.users.delete-plan', $plan) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No estate plans</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by uploading a document.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Intake Submission Section --}}
                @if($user->intakeSubmission)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">
                            Intake Submission
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach(collect([$user->intakeSubmission]) as $submission)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition">
                                <div class="mb-2 sm:mb-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        Intake Form Submission
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $submission->created_at->format('M d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.users.download-intake', $user) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 w-full sm:w-auto">
                                    View Details
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

{{-- Upload Modal --}}
<div id="upload-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-screen overflow-y-auto">
        <form action="{{ route('admin.users.upload-plan', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Upload Estate Plan Document
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="document_name" class="block text-sm font-medium text-gray-700">
                            Document Name *
                        </label>
                        <input type="text" 
                               name="document_name" 
                               id="document_name" 
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <select name="status" 
                                id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="draft">Draft</option>
                            <option value="final" selected>Final</option>
                            <option value="executed">Executed</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="executed_date" class="block text-sm font-medium text-gray-700">
                            Executed Date (Optional)
                        </label>
                        <input type="date" 
                               name="executed_date" 
                               id="executed_date"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">
                            PDF Document *
                        </label>
                        <input type="file" 
                               name="file" 
                               id="file" 
                               accept=".pdf"
                               required
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                <button type="button" 
                        onclick="document.getElementById('upload-modal').classList.add('hidden')"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Upload Document
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
