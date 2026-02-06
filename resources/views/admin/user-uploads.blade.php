@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.users.show', $user) }}" class="text-purple-600 hover:text-purple-800 text-sm mb-2 inline-block">
            ← Back to {{ $user->name }}
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Documents for {{ $user->name }}</h1>
                <p class="mt-2 text-gray-600">{{ $user->email }}</p>
            </div>
            
            @if($uploads->flatten()->count() > 0)
                <a href="{{ route('admin.uploads.zip', $user) }}" 
                   class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download All (ZIP)
                </a>
            @endif
        </div>
    </div>

    {{-- Uploads by Category --}}
    @if($uploads->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600 text-lg">This client hasn't uploaded any documents yet</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($categories as $categoryKey => $categoryLabel)
                @if(isset($uploads[$categoryKey]) && $uploads[$categoryKey]->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        {{-- Category Header --}}
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center justify-between">
                                <span>{{ $categoryLabel }}</span>
                                <span class="text-purple-200 text-sm">{{ $uploads[$categoryKey]->count() }} {{ $uploads[$categoryKey]->count() == 1 ? 'file' : 'files' }}</span>
                            </h3>
                        </div>

                        {{-- Files List --}}
                        <div class="divide-y divide-gray-200">
                            @foreach($uploads[$categoryKey] as $upload)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex items-center space-x-4 flex-1">
                                        {{-- File Icon --}}
                                        <div class="flex-shrink-0">
                                            @if($upload->isPdf())
                                                <svg class="h-10 w-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                                </svg>
                                            @elseif($upload->isImage())
                                                <svg class="h-10 w-10 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg class="h-10 w-10 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- File Info --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $upload->original_name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $upload->formatted_size }} • 
                                                Uploaded {{ $upload->created_at->format('M j, Y') }} at {{ $upload->created_at->format('g:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center space-x-3 ml-4">
                                        <a href="{{ route('admin.uploads.download', $upload) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>

                                        <form action="{{ route('admin.uploads.delete', $upload) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
