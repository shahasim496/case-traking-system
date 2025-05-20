<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0066cc;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
        .attachment-note {
            background-color: #e8f4f8;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Evidence Report Ready</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $evidence->officer_name }},</p>
            
            <p>This is to inform you that the evidence report for your submission is now ready.</p>
            
            <div class="details">
                <h3>Evidence Details:</h3>
                <p><strong>Reference Number:</strong> {{ $referenceNumber }}</p>
                <p><strong>Type:</strong> {{ ucfirst($evidence->type) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($evidence->status) }}</p>
                <p><strong>Submission Date:</strong> {{ $evidence->created_at->format('Y-m-d H:i') }}</p>
                @if($evidence->notes)
                    <p><strong>Notes:</strong> {{ $evidence->notes }}</p>
                @endif
            </div>

            <div class="attachment-note">
                <p><strong>Note:</strong> The complete evidence report is attached to this email for your reference.</p>
            </div>

            <p>You can also view the report by logging into the system.</p>
            
            <p>If you have any questions, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            GFSL Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html> 