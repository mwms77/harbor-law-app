@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Document Uploads</h1>
            <p class="mt-2 text-gray-600">Securely upload your estate planning documents</p>
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

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Upload New Documents</h2>
            
            <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <!-- Category Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Document Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select a category...</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Please categorize your documents to help us organize them properly</p>
                </div>

                <!-- File Upload Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Files <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                    <span>Upload files</span>
                                    <input id="file-upload" name="files[]" type="file" multiple required accept=".pdf,.jpg,.jpeg,.png,.heic" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, JPG, PNG, HEIC up to 10MB each
                            </p>
                        </div>
                    </div>
                    <div id="fileList" class="mt-3 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Selected files:</p>
                        <ul id="fileListItems" class="text-sm text-gray-600 space-y-1"></ul>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md">
                        Upload Documents
                    </button>
                </div>
            </form>
        </div>

        <!-- Uploaded Documents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Your Uploaded Documents</h2>
            
            @if($uploads->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No documents uploaded yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by uploading your first document above.</p>
                </div>
            @else
                @foreach($categories as $categoryKey => $categoryLabel)
                    @if(isset($uploads[$categoryKey]) && $uploads[$categoryKey]->count() > 0)
                        <div class="mb-6 last:mb-0">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                <span class="h-2 w-2 bg-purple-600 rounded-full mr-2"></span>
                                {{ $categoryLabel }}
                                <span class="ml-2 text-sm text-gray-500">({{ $uploads[$categoryKey]->count() }})</span>
                            </h3>
                            <div class="space-y-2">
                                @foreach($uploads[$categoryKey] as $upload)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <svg class="h-8 w-8 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                @if($upload->file_icon === 'photograph')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                @endif
                                            </svg>
                                            <div class="ml-3 flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $upload->original_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $upload->formatted_size }} • Uploaded {{ $upload->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('uploads.download', $upload) }}" class="ml-4 px-4 py-2 text-sm font-medium text-purple-600 hover:text-purple-700 hover:bg-purple-50 rounded-md transition-colors">
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

        <!-- Important Information -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>All files are stored securely and encrypted</li>
                            <li>Maximum file size: 10MB per file</li>
                            <li>Accepted formats: PDF, JPG, PNG, HEIC</li>
                            <li>You can upload multiple files at once</li>
                            <li>To remove a document, please contact us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('file-upload').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    const fileListItems = document.getElementById('fileListItems');
    const files = e.target.files;
    
    if (files.length > 0) {
        fileList.classList.remove('hidden');
        fileListItems.innerHTML = '';
        
        for (let i = 0; i < files.length; i++) {
            const li = document.createElement('li');
            const size = (files[i].size / 1024 / 1024).toFixed(2);
            li.textContent = `${files[i].name} (${size} MB)`;
            fileListItems.appendChild(li);
        }
    } else {
        fileList.classList.add('hidden');
    }
});
</script>
@endsection
