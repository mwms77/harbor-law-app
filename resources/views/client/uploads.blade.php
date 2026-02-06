@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Documents</h1>
        <p class="mt-2 text-gray-600">Upload and manage your estate planning documents securely</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Upload Form --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload New Documents</h2>
        
        <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            
            {{-- Category Selection --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Document Category <span class="text-red-500">*</span>
                </label>
                <select name="category" required 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Select a category...</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Choose the category that best describes your documents</p>
            </div>

            {{-- File Upload Area --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Files <span class="text-red-500">*</span>
                </label>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-500 transition-colors cursor-pointer"
                     id="dropZone">
                    <input type="file" 
                           name="files[]" 
                           id="fileInput" 
                           multiple 
                           accept=".pdf,.jpg,.jpeg,.png,.heic"
                           class="hidden"
                           required>
                    
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    
                    <p class="text-gray-600 mb-2">
                        <span class="font-semibold text-purple-600">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-sm text-gray-500">PDF, JPG, PNG, or HEIC (max 10MB per file)</p>
                </div>

                <div id="fileList" class="mt-4 space-y-2"></div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-indigo-700 transition-all">
                    Upload Documents
                </button>
            </div>
        </form>
    </div>

    {{-- Uploaded Documents by Category --}}
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">Your Uploaded Documents</h2>

        @if($uploads->isEmpty())
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-600">No documents uploaded yet</p>
                <p class="text-sm text-gray-500 mt-1">Upload your first document using the form above</p>
            </div>
        @else
            @foreach($categories as $categoryKey => $categoryLabel)
                @if(isset($uploads[$categoryKey]) && $uploads[$categoryKey]->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">
                                {{ $categoryLabel }}
                                <span class="ml-2 text-purple-200">({{ $uploads[$categoryKey]->count() }})</span>
                            </h3>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($uploads[$categoryKey] as $upload)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                                    <div class="flex items-center space-x-4">
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
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $upload->original_name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $upload->formatted_size }} • Uploaded {{ $upload->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Download Button --}}
                                    <a href="{{ route('uploads.download', $upload) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Drag and drop functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-purple-500', 'bg-purple-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-purple-500', 'bg-purple-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-purple-500', 'bg-purple-50');
        fileInput.files = e.dataTransfer.files;
        displayFiles();
    });

    fileInput.addEventListener('change', displayFiles);

    function displayFiles() {
        fileList.innerHTML = '';
        const files = fileInput.files;

        if (files.length === 0) return;

        for (let file of files) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-gray-50 p-3 rounded-lg';
            fileItem.innerHTML = `
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${file.name}</p>
                        <p class="text-xs text-gray-500">${fileSize} MB</p>
                    </div>
                </div>
            `;
            fileList.appendChild(fileItem);
        }
    }
</script>
@endpush
@endsection
