<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Case Forwarded</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #00349C; border-bottom: 2px solid #00349C; padding-bottom: 10px;">
            Case Forwarded
        </h2>
        
        <p>Dear {{ $data['receiverName'] }},</p>
        
        <p>A case has been forwarded to you by <strong>{{ $data['senderName'] }}</strong>.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #00349C; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #00349C;">Case Details:</h3>
            <p><strong>Case Number:</strong> {{ $data['case']->case_number }}</p>
            <p><strong>Case Title:</strong> {{ $data['case']->case_title }}</p>
            <p><strong>Court Type:</strong> {{ $data['case']->court_type }}</p>
            @if($data['case']->department)
                <p><strong>Department:</strong> {{ $data['case']->department->name }}</p>
            @endif
        </div>
        
        @if(!empty($data['message']))
        <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #856404;">Message:</h3>
            <p style="white-space: pre-wrap;">{{ $data['message'] }}</p>
        </div>
        @endif
        
        <p>
            <a href="{{ route('cases.show', $data['case']->id) }}" 
               style="display: inline-block; padding: 10px 20px; background-color: #00349C; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;">
                View Case Details
            </a>
        </p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #666;">
            This is an automated notification from the Court System.
        </p>
    </div>
</body>
</html>

