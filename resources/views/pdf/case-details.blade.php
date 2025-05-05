<!-- filepath: resources/views/pdf/case-details.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Case Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .section p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Case Details -->
    <div class="section">
        <h2>Case Details</h2>
        <p><strong>Case ID:</strong> {{ $case->CaseID }}</p>
        <p><strong>Case Type:</strong> {{ $case->CaseType }}</p>
        <p><strong>Case Status:</strong> {{ $case->CaseStatus }}</p>
        <p><strong>Description:</strong> {{ $case->CaseDescription }}</p>
    </div>

    <!-- Complainant Details -->
    <div class="section">
        <h2>Complainant Details</h2>
        <p><strong>Name:</strong> {{ $complainant->ComplainantName }}</p>
        <p><strong>Contact:</strong> {{ $complainant->ComplainantContact }}</p>
        <p><strong>ID Number:</strong> {{ $complainant->ComplainantID }}</p>
        <p><strong>Date of Birth:</strong> {{ $complainant->ComplainantDateOfBirth }}</p>
        <p><strong>Gender:</strong> {{ $complainant->ComplainantGenderType }}</p>
        <p><strong>Address:</strong> {{ $complainant->ComplainantAddress }}</p>
        <p><strong>Other Details:</strong> {{ $complainant->ComplainantOtherDetails }}</p>
    </div>

    <!-- Accused Details -->
    <div class="section">
        <h2>Accused Details</h2>
        <p><strong>Name:</strong> {{ $accused->AccusedName }}</p>
        <p><strong>Contact:</strong> {{ $accused->AccusedContact }}</p>
        <p><strong>ID Number:</strong> {{ $accused->AccusedID }}</p>
        <p><strong>Date of Birth:</strong> {{ $accused->AccusedDateOfBirth }}</p>
        <p><strong>Gender:</strong> {{ $accused->AccusedGenderType }}</p>
        <p><strong>Address:</strong> {{ $accused->AccusedAddress }}</p>
        <p><strong>Other Details:</strong> {{ $accused->AccusedOtherDetails }}</p>
    </div>
</body>
</html>