@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    {{-- Header --}}
    <div class="mb-8 flex justify-between items-start">
        <div>
            <a href="{{ route('admin.users') }}" class="text-purple-600 hover:text-purple-800 text-sm mb-2 inline-block">
                ← Back to Users
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="mt-1 text-gray-600">{{ $user->email }}</p>
        </div>
        
        @if($user->uploads->count() > 0)
            <a href="{{ route('admin.uploads.zip', $user) }}" 
               class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download All Files (ZIP)
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- User Information Card --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Information</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Status</label>
                        <form action="{{ route('admin.users.status', $user) }}" method="POST" class="mt-1">
                            @csrf
                            @method('PATCH')
                            <select name="status" 
                                    onchange="this.form.submit()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $user->status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Current Status</label>
                        <div class="mt-1">
                            <span class="px-3 py-2 text-sm font-semibold rounded-lg inline-block {{ $user->status_color }}">
                                {{ $user->status_name }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Registered</label>
                        <p class="mt-1 text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Intake Status</label>
                        @if($user->hasCompletedIntake())
                            <p class="mt-1 text-green-600 font-medium">✓ Completed</p>
                            <p class="text-sm text-gray-500">{{ $user->intake_completed_at->format('M j, Y') }}</p>
                        @else
                            <p class="mt-1 text-gray-400">Not started</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Documents Uploaded</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $user->uploads->count() }} files</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Last Upload</label>
                        @if($user->uploads->count() > 0)
                            <p class="mt-1 text-gray-900">{{ $user->uploads->first()->created_at->format('M j, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $user->uploads->first()->created_at->diffForHumans() }}</p>
                        @else
                            <p class="mt-1 text-gray-400">No uploads</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Uploads --}}
            @if($user->uploads->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Recent Documents</h2>
                        <a href="{{ route('admin.uploads.user', $user) }}" 
                           class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                            View All →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach($user->uploads->take(5) as $upload)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0">
                                <div class="flex items-center space-x-3">
                                    @if($upload->isPdf())
                                        <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                        </svg>
                                    @else
                                        <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $upload->original_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $upload->category_name }} • {{ $upload->formatted_size }} • {{ $upload->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ route('admin.uploads.download', $upload) }}" 
                                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Admin Notes --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Notes</h3>

                {{-- Add Note Form --}}
                <form action="{{ route('admin.users.notes.add', $user) }}" method="POST" class="mb-4">
                    @csrf
                    <textarea name="note" 
                              rows="3" 
                              placeholder="Add a private note..."
                              required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                    <button type="submit" 
                            class="mt-2 w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        Add Note
                    </button>
                </form>

                {{-- Notes List --}}
                <div class="space-y-3">
                    @forelse($user->adminNotes()->orderBy('created_at', 'desc')->get() as $note)
                        <div class="bg-gray-50 rounded-lg p-3 relative">
                            <p class="text-sm text-gray-800 mb-2">{{ $note->note }}</p>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-500">{{ $note->created_at->format('M j, Y g:i A') }}</p>
                                <form action="{{ route('admin.users.notes.delete', $note) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this note?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No notes yet</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-2">
                    @if($user->hasCompletedIntake())
                        <a href="#" class="block w-full text-center bg-purple-100 text-purple-700 px-4 py-2 rounded-lg hover:bg-purple-200 transition-colors">
                            View Intake Form
                        </a>
                    @endif

                    @if($user->uploads->count() > 0)
                        <a href="{{ route('admin.uploads.user', $user) }}" 
                           class="block w-full text-center bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                            View All Documents
                        </a>
                        
                        <a href="{{ route('admin.uploads.zip', $user) }}" 
                           class="block w-full text-center bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-colors">
                            Download ZIP
                        </a>
                    @endif

                    <a href="mailto:{{ $user->email }}" 
                       class="block w-full text-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        Send Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
