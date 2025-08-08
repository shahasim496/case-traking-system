<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $jobPosting->title }} - Job Details</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80');
            background-size: cover;
            background-position: center;
            padding: 60px 0;
            position: relative;
        }
        
        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .breadcrumb-nav {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 30px;
        }
        
        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: #0056b3;
        }
        
        .job-detail-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .job-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        
        .job-title {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .meta-icon {
            color: #007bff;
            width: 20px;
        }
        
        .status-badge {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .job-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .section-content {
            color: #495057;
            line-height: 1.8;
            font-size: 1rem;
        }
        
        .requirements-list {
            list-style: none;
            padding: 0;
        }
        
        .requirements-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
            position: relative;
            padding-left: 25px;
        }
        
        .requirements-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }
        
        .job-dates {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 30px;
        }
        
        .dates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .date-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .date-icon {
            width: 40px;
            height: 40px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        
        .date-info h5 {
            margin: 0;
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 600;
        }
        
        .date-info p {
            margin: 0;
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .deadline .date-info p {
            color: #dc3545;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        
        .btn-primary {
            background: #007bff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            text-decoration: none;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 20px;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        @media (max-width: 768px) {
            .job-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .dates-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="page-title text-center">Job Details</h1>
                    
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('careers') }}">Current Openings</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $jobPosting->title }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="job-detail-card">
                    <div class="job-header">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="flex-grow-1">
                                <h1 class="job-title">{{ $jobPosting->title }}</h1>
                                
                                <div class="job-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-building meta-icon"></i>
                                        <span>{{ $jobPosting->department->name ?? 'Not Specified' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users meta-icon"></i>
                                        <span>{{ $jobPosting->positions }} Position(s) Available</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt meta-icon"></i>
                                        <span>{{ $jobPosting->department->name ?? 'Not Specified' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user meta-icon"></i>
                                        <span>Age Limit: 30 Years</span>
                                    </div>
                                </div>
                            </div>
                            <div class="status-badge">
                                <i class="fas fa-check-circle me-2"></i>Active
                            </div>
                        </div>
                    </div>

                                         <div class="job-section">
                         <h3 class="section-title">
                             <i class="fas fa-graduation-cap me-2"></i>Qualifications
                         </h3>
                         <div class="section-content">
                             {!! $jobPosting->requirements !!}
                         </div>
                     </div>

                                         <div class="job-section">
                         <h3 class="section-title">
                             <i class="fas fa-briefcase me-2"></i>Job Description
                         </h3>
                         <div class="section-content">
                             {!! $jobPosting->description !!}
                         </div>
                     </div>

                    <div class="job-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle me-2"></i>Additional Information
                        </h3>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Job Location:</strong> {{ $jobPosting->department->name ?? 'Not Specified' }}</p>
                                    <p><strong>Employment Type:</strong> Contractual</p>
                                    <p><strong>Experience Level:</strong> Preferably 0-2 years of post-qualification experience</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Positions Available:</strong> {{ $jobPosting->positions }}</p>
                                    <p><strong>Department:</strong> {{ $jobPosting->department->name ?? 'Not Specified' }}</p>
                                    <p><strong>Posted On:</strong> {{ $jobPosting->created_at->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

             

                    <div class="job-dates">
                        <h4 class="section-title mb-4">
                            <i class="fas fa-calendar-alt me-2"></i>Important Dates
                        </h4>
                        <div class="dates-grid">
                            <div class="date-item">
                                <div class="date-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="date-info">
                                    <h5>Opening Date</h5>
                                    <p>{{ $jobPosting->created_at->format('d-M-Y') }}</p>
                                </div>
                            </div>
                            <div class="date-item deadline">
                                <div class="date-icon" style="background: #dc3545;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="date-info">
                                    <h5>Last Date to Apply</h5>
                                    <p>{{ $jobPosting->deadline->format('d-M-Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('careers') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to All Jobs
                        </a>
                        <button class="btn btn-primary" onclick="alert('Application functionality will be implemented here. For now, please contact the HR department directly.')">
                            <i class="fas fa-paper-plane me-2"></i>Apply Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 