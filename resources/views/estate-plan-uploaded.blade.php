<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .document-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .document-name {
            font-size: 18px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            background: #667eea;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ðŸ“„ New Document Available</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $userName }},</p>
        
        <p>Your estate planning documents are ready! A new document has been uploaded to your account.</p>
        
        <div class="document-box">
            <div class="document-name">{{ $documentName }}</div>
            <div><strong>Status:</strong> <span class="status-badge">{{ $status }}</span></div>
            <div style="color: #6c757d; font-size: 14px; margin-top: 8px;">Uploaded on {{ $uploadDate }}</div>
        </div>
        
        <p>You can view and download your document from your dashboard.</p>
        
        <a href="{{ $dashboardUrl }}" class="button">View My Documents</a>
        
        <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
            If you have any questions about your estate plan documents, please contact your attorney.
        </p>
    </div>
    
    <div class="footer">
        <p>Â© {{ date('Y') }} Harbor Law. All rights reserved.</p>
        <p>This is an automated notification. Please do not reply to this email.</p>
    </div>
</body>
</html>
