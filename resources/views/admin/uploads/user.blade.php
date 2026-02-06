@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Documents for {{ $user->full_name }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($uploads->count() > 0)
                    <a href="{{ route('admin.uploads.zip', $user) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download All as ZIP
                    </a>
                @endif
                <a href="{{ route('admin.users.show', $user) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Back to User
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Uploads by Category -->
        <div class="space-y-6">
            @if($uploads->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No documents uploaded</h3>
                    <p class="mt-1 text-sm text-gray-500">This user has not uploaded any documents yet.</p>
                </div>
            @else
                @foreach($categories as $categoryKey => $categoryLabel)
                    @if(isset($uploads[$categoryKey]) && $uploads[$categoryKey]->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <span class="h-2 w-2 bg-purple-600 rounded-full mr-2"></span>
                                    {{ $categoryLabel }}
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $uploads[$categoryKey]->count() }} {{ $uploads[$categoryKey]->count() === 1 ? 'file' : 'files' }}
                                    </span>
                                </h3>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($uploads[$categoryKey] as $upload)
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <svg class="h-10 w-10 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if($upload->file_icon === 'photograph')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    @endif
                                                </svg>
                                                <div class="ml-4 flex-1 min-w-0">
                                                    <p class="text-base font-medium text-gray-900 truncate">{{ $upload->original_name }}</p>
                                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                        <span>{{ $upload->formatted_size }}</span>
                                                        <span>•</span>
                                                        <span>{{ $upload->created_at->format('M j, Y g:i A') }}</span>
                                                        <span>•</span>
                                                        <span>{{ $upload->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex items-center space-x-2">
                                                <a href="{{ route('admin.uploads.download', $upload) }}" class="px-4 py-2 text-sm font-medium text-purple-600 hover:text-purple-700 hover:bg-purple-50 rounded-md transition-colors">
                                                    Download
                                                </a>
                                                <form action="{{ route('admin.uploads.delete', $upload) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        <!-- Summary Statistics -->
        @if($uploads->count() > 0)
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Upload Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-2xl font-bold text-blue-900">{{ $uploads->count() }}</p>
                        <p class="text-sm text-blue-700">Total Files</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-900">{{ number_format($uploads->sum('file_size') / 1024 / 1024, 2) }} MB</p>
                        <p class="text-sm text-blue-700">Total Size</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-900">{{ $uploads->unique('category')->count() }}</p>
                        <p class="text-sm text-blue-700">Categories Used</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
