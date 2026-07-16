<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body  { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 24px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 12px;
                 background: #fee2e2; color: #b91c1c; font-size: 13px; }
        .reason-box { background: #f9fafb; border-left: 4px solid #e5e7eb;
                      padding: 12px 16px; margin: 16px 0; border-radius: 4px; }
        .btn { display: inline-block; padding: 10px 24px; background: #4f46e5;
               color: #ffffff; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <h2>Delete Request Submitted</h2>
    <p>
        <strong>{{ $requester->name }}</strong> ({{ $requester->email }}) has requested
        to delete the following {{ $deleteRequest->target_type }}:
    </p>
    <p><strong>Name:</strong> {{ $target->name }}</p>
    <p><strong>Type:</strong> <span class="badge">{{ $deleteRequest->target_type }}</span></p>
    <div class="reason-box">
        <strong>Reason / Justification:</strong><br>
        {{ $deleteRequest->reason }}
    </div>
    <p>Please log in to the admin panel to review and approve or reject this request.</p>
    <a href="{{ url('/admin/delete-requests') }}" class="btn">Review Request</a>
    <p class="footer">
        This email was sent automatically from {{ config('app.name') }}.
    </p>
</div>
</body>
</html>