@extends('layouts.app')

@section('title', 'Document Uploads')

@section('content')

<div class="header">
    <h1>Document Uploads</h1>
    <p>Securely upload your estate planning documents</p>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Upload New Documents</h2>
    
    <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        
        <div class="form-group">
            <label>Document Category <span style="color: #dc3545;">*</span></label>
            <select name="category" required>
                <option value="">Select a category...</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <p style="margin-top: 6px; font-size: 13px; color: #666;">Please categorize your documents to help us organize them properly.</p>
        </div>

        <div class="form-group">
            <label>Select Files <span style="color: #dc3545;">*</span></label>
            <div style="border: 2px dashed #e0e0e0; border-radius: 8px; padding: 24px; text-align: center; background: #fafafa;">
                <p style="margin-bottom: 12px; color: #555;">Drag and drop files here, or click to choose</p>
                <label for="file-upload" class="btn btn-primary" style="cursor: pointer; margin-bottom: 8px;">
                    Choose Files
                </label>
                <input id="file-upload" name="files[]" type="file" multiple required accept=".pdf,.jpg,.jpeg,.png,.heic" style="position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden;">
                <p style="font-size: 12px; color: #666;">PDF, JPG, PNG, HEIC — max 10MB each</p>
            </div>
            <div id="fileList" style="margin-top: 12px; display: none;">
                <p style="font-weight: 600; margin-bottom: 8px; font-size: 14px;">Selected files:</p>
                <ul id="fileListItems" style="margin-left: 20px; font-size: 14px; color: #555;"></ul>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Upload Documents</button>
    </form>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Your Uploaded Documents</h2>
    
    @if($uploads->isEmpty())
        <div style="text-align: center; padding: 40px; color: #666;">
            <p style="margin-bottom: 8px; font-size: 16px;">No documents uploaded yet.</p>
            <p style="font-size: 14px;">Get started by uploading your first document above.</p>
        </div>
    @else
        @foreach($categories as $categoryKey => $categoryLabel)
            @if(isset($uploads[$categoryKey]) && $uploads[$categoryKey]->count() > 0)
                <div style="margin-bottom: 24px;">
                    <h3 style="color: #333; font-size: 16px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #eee;">
                        <span style="display: inline-block; width: 8px; height: 8px; background: #667eea; border-radius: 50%; margin-right: 8px; vertical-align: middle;"></span>
                        {{ $categoryLabel }}
                        <span style="font-weight: normal; color: #666; font-size: 14px;">({{ $uploads[$categoryKey]->count() }})</span>
                    </h3>
                    <div style="border: 1px solid #eee; border-radius: 6px; overflow: hidden;">
                        @foreach($uploads[$categoryKey] as $upload)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-bottom: 1px solid #f0f0f0; flex-wrap: wrap; gap: 10px;">
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-weight: 600; color: #333; margin-bottom: 4px; word-break: break-word;">{{ $upload->original_name }}</p>
                                    <p style="font-size: 12px; color: #666;">{{ $upload->formatted_size }} · {{ $upload->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                                <a href="{{ route('uploads.download', $upload) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">Download</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>

<div class="card" style="background: #f0f4ff; border: 1px solid #c5d3ff;">
    <h3 style="color: #667eea; margin-bottom: 12px; font-size: 16px;">Important Information</h3>
    <ul style="margin-left: 20px; color: #4a5568; font-size: 14px; line-height: 1.6;">
        <li>All files are stored securely</li>
        <li>Maximum file size: 10MB per file</li>
        <li>Accepted formats: PDF, JPG, PNG, HEIC</li>
        <li>You can upload multiple files at once</li>
        <li>To remove a document, please contact us</li>
    </ul>
</div>

<script>
document.getElementById('file-upload').addEventListener('change', function(e) {
    var fileList = document.getElementById('fileList');
    var fileListItems = document.getElementById('fileListItems');
    var files = e.target.files;
    
    if (files.length > 0) {
        fileList.style.display = 'block';
        fileListItems.innerHTML = '';
        for (var i = 0; i < files.length; i++) {
            var li = document.createElement('li');
            var size = (files[i].size / 1024 / 1024).toFixed(2);
            li.textContent = files[i].name + ' (' + size + ' MB)';
            fileListItems.appendChild(li);
        }
    } else {
        fileList.style.display = 'none';
    }
});
</script>
@endsection
