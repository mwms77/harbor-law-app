<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Important Contacts - {{ $user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        h1 { color: #667eea; font-size: 22px; margin-bottom: 8px; }
        .subtitle { color: #666; font-size: 14px; margin-bottom: 24px; }
        .section { margin-bottom: 20px; }
        .section-title { background: #667eea; color: white; padding: 8px 12px; font-size: 14px; font-weight: bold; margin-bottom: 10px; }
        .contact { margin-bottom: 14px; padding: 10px; background: #f8f9fa; border-left: 3px solid #667eea; }
        .contact-name { font-weight: bold; font-size: 13px; margin-bottom: 4px; }
        .contact-detail { margin: 2px 0; font-size: 11px; color: #555; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <h1>Important Contacts</h1>
    <p class="subtitle">{{ $user->name }} — Generated {{ now()->format('F j, Y') }}</p>

    @php $order = array_keys($roleLabels); @endphp
    @foreach($order as $roleType)
        @if($contactsByRole->has($roleType))
            @php $contacts = $contactsByRole->get($roleType); $label = $roleLabels[$roleType] ?? $roleType; @endphp
            <div class="section">
                <div class="section-title">{{ $label }}</div>
                @foreach($contacts as $contact)
                    <div class="contact">
                        <div class="contact-name">{{ $contact->full_name }}@if($contact->relationship) — {{ $contact->relationship }}@endif</div>
                        @if($contact->phone)<div class="contact-detail">Phone: {{ $contact->phone }}</div>@endif
                        @if($contact->email)<div class="contact-detail">Email: {{ $contact->email }}</div>@endif
                        @if($contact->address)<div class="contact-detail">Address: {{ $contact->address }}</div>@endif
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <div class="footer">Harbor Law Estate Planning — Important Contacts</div>
</body>
</html>
