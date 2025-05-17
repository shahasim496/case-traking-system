<!DOCTYPE html>
<html>
<head>
    <title>Evidence Submission Confirmation</title>
</head>
<body>
    <h2>Evidence Submission Confirmation</h2>
    
    <p>Dear {{ $evidence->officer_name }},</p>
    
    <p>This email confirms that your evidence has been successfully submitted to the GUYANA FORENSIC SCIENCE LABORATORY.</p>
    
    <h3>Evidence Details:</h3>
    <ul>
        <li><strong>Evidence Type:</strong> {{ ucfirst($evidence->type) }}</li>
        <li><strong>Submission Date:</strong> {{ $evidence->created_at->format('F d, Y H:i:s') }}</li>
    </ul>
    
    <h3>Officer Information:</h3>
    <ul>
        <li><strong>Officer ID:</strong> {{ $evidence->g_officer_id }}</li>
        <li><strong>Officer Name:</strong> {{ $evidence->g_officer_name }}</li>
        <li><strong>Email:</strong> {{ $evidence->g_designation }}</li>
    </ul>
    
    <p>Your evidence has been received and will be processed accordingly. You will be notified of any updates regarding this submission.</p>
    
    <p>Thank you for your submission.</p>
    
    <p>Best regards,<br>
    GUYANA FORENSIC SCIENCE LABORATORY</p>
</body>
</html>