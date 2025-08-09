<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Openings - Job Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
                 body {
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             background: #f8f9fa;
             min-height: 100vh;
             margin: 0;
             padding: 0;
         }
        
                 .hero-section {
             background: linear-gradient(#00349C, #003493);
             padding: 80px 0;
             position: relative;
             margin-bottom: 50px;
         }
        
                 .page-title {
             color: white;
             font-size: 2.5rem;
             font-weight: 700;
             margin-bottom: 30px;
             text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
         }
        
        .search-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            margin-bottom: 50px;
        }
        
        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            border: 2px solid #00349C;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #00349C;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
            outline: none;
        }
        
        .search-btn {
            background: #00349C;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
                 .job-card {
             background: white;
             border-radius: 15px;
             padding: 25px;
             margin-bottom: 25px;
             box-shadow: 0 5px 15px rgba(0,0,0,0.1);
             transition: all 0.3s ease;
             border: 1px solid #e9ecef;
         }
        
                 .job-card:hover {
             transform: translateY(-5px);
             box-shadow: 0 10px 25px rgba(0,0,0,0.15);
         }
         
         .job-header {
             border-bottom: 1px solid #e9ecef;
             padding-bottom: 15px;
             margin-bottom: 20px;
         }
         
         .job-content {
             padding-top: 10px;
         }
        
        .job-title {
            color: #2c3e50;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .job-location {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }
        
        .location-icon {
            color: #dc3545;
            margin-right: 5px;
        }
        
                 .view-detail-btn {
             background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
             color: white;
             border: none;
             border-radius: 12px;
             padding: 12px 24px;
             font-size: 0.9rem;
             font-weight: 600;
             text-decoration: none;
             transition: all 0.3s ease;
             display: inline-block;
             box-shadow: 0 4px 15px rgba(0,123,255,0.3);
             position: relative;
             overflow: hidden;
         }
         
         .view-detail-btn:hover {
             background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
             color: white;
             text-decoration: none;
             transform: translateY(-2px);
             box-shadow: 0 6px 20px rgba(0,123,255,0.4);
         }
         
         .view-detail-btn:active {
             transform: translateY(0);
             box-shadow: 0 2px 10px rgba(0,123,255,0.3);
         }
        
        .job-section {
            margin-bottom: 15px;
        }
        
        .section-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .section-content {
            color: #6c757d;
            line-height: 1.6;
        }
        
        .job-dates {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        
        .date-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .date-icon {
            color: #6c757d;
        }
        
        .deadline {
            color: #dc3545;
            font-weight: 600;
        }
        
        .no-jobs {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        
                 .pagination-container {
             display: flex;
             justify-content: center;
             margin-top: 40px;
         }
         
         .pagination {
             display: flex;
             list-style: none;
             padding: 0;
             margin: 0;
             gap: 5px;
         }
         
         .page-item {
             margin: 0;
         }
         
         .page-link {
             color: #007bff;
             border: 2px solid #e9ecef;
             padding: 12px 18px;
             border-radius: 10px;
             text-decoration: none;
             font-weight: 600;
             transition: all 0.3s ease;
             background: white;
             display: block;
             min-width: 45px;
             text-align: center;
         }
         
         .page-link:hover {
             background: #007bff;
             color: white;
             border-color: #007bff;
             transform: translateY(-2px);
             box-shadow: 0 4px 8px rgba(0,123,255,0.3);
         }
         
         .page-item.active .page-link {
             background: #007bff;
             border-color: #007bff;
             color: white;
             box-shadow: 0 4px 8px rgba(0,123,255,0.3);
         }
         
         .page-item.disabled .page-link {
             color: #6c757d;
             border-color: #e9ecef;
             background: #f8f9fa;
             cursor: not-allowed;
         }
         
         .page-item.disabled .page-link:hover {
             background: #f8f9fa;
             color: #6c757d;
             border-color: #e9ecef;
             transform: none;
             box-shadow: none;
         }
         
         @media (max-width: 768px) {
             .pagination {
                 gap: 3px;
             }
             
             .page-link {
                 padding: 10px 14px;
                 min-width: 40px;
                 font-size: 0.9rem;
             }
             
             .job-header .d-flex {
                 flex-direction: column;
                 align-items: stretch !important;
             }
             
             .job-header .ms-3 {
                 margin-left: 0 !important;
                 margin-top: 15px;
                 text-align: center;
             }
             
             .view-detail-btn {
                 width: 100%;
                 text-align: center;
                 padding: 15px 20px;
                 font-size: 1rem;
             }
         }
    </style>
</head>
<body>
         <div class="hero-section">
         <div class="container">
             <div class="row justify-content-center">
                 <div class="col-lg-10">
                     <h1 class="page-title text-center">Current Openings</h1>
                     
                     <div class="search-container">
                         <form method="GET" action="{{ route('careers') }}" class="search-form">
                             <input type="text" 
                                    name="search" 
                                    class="form-control search-input" 
                                    placeholder="Search jobs, departments, or keywords..." 
                                    value="{{ request('search') }}">
                             <button type="submit" class="search-btn">
                                 <i class="fas fa-search"></i>
                             </button>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 
     <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($jobPostings->count() > 0)
                    @foreach($jobPostings as $jobPosting)
                                                 <div class="job-card">
                             <div class="job-header">
                                 <div class="d-flex justify-content-between align-items-start mb-3">
                                     <div class="flex-grow-1">
                                         <h3 class="job-title">{{ $jobPosting->title }}</h3>
                                                                                 <div class="job-location">
                                            <i class="fas fa-map-marker-alt location-icon"></i>
                                            {{ $jobPosting->department->name ?? 'Not Specified' }}
                                            @if($jobPosting->designation)
                                                • {{ $jobPosting->designation->name }}
                                            @endif
                                            @if($jobPosting->age_limit)
                                                <span class="ms-3">(Age Limit: {{ $jobPosting->age_limit }} Years)</span>
                                            @endif
                                        </div>
                                        <div class="mt-2">
                                            @if($jobPosting->pay_scale)
                                                <span class="badge bg-success me-2">{{ $jobPosting->pay_scale }}</span>
                                            @endif
                                            @if($jobPosting->job_type)
                                                <span class="badge bg-primary me-2">{{ ucwords(str_replace('_', ' ', $jobPosting->job_type)) }}</span>
                                            @endif
                                            @if($jobPosting->gender && $jobPosting->gender != 'any')
                                                <span class="badge bg-info">{{ ucwords($jobPosting->gender) }} Preferred</span>
                                            @endif
                                        </div>
                                     </div>
                                     <div class="ms-3">
                                         <div class="d-flex flex-column gap-2">
                                             @if($jobPosting->job_advertisement)
                                                 <a href="{{ Storage::url($jobPosting->job_advertisement) }}" target="_blank" class="btn btn-danger btn-sm">
                                                     <i class="fas fa-eye me-1"></i>View PDF
                                                 </a>
                                             @endif
                                             <a href="{{ route('careers.show', $jobPosting->id) }}" class="view-detail-btn">
                                                 <i class="fas fa-eye me-2"></i>VIEW DETAIL
                                             </a>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             
                             <div class="job-content">
                                 <div class="job-section">
                                     <div class="section-title">Qualifications:</div>
                                     <div class="section-content">
                                         {!! Str::limit(strip_tags($jobPosting->requirements), 150) !!}
                                         @if(strlen(strip_tags($jobPosting->requirements)) > 150)
                                             <span class="text-primary">...</span>
                                         @endif
                                     </div>
                                 </div>
                                 
                                 <div class="job-section">
                                     <div class="section-title">Job Description:</div>
                                     <div class="section-content">
                                         {!! Str::limit(strip_tags($jobPosting->description), 200) !!}
                                         @if(strlen(strip_tags($jobPosting->description)) > 200)
                                             <span class="text-primary">...</span>
                                         @endif
                                     </div>
                                 </div>
                                 
                                                                 <div class="job-section">
                                    <div class="section-title">Additional Information:</div>
                                    <div class="section-content">
                                        <strong>Job Location:</strong> {{ $jobPosting->department->name ?? 'Not Specified' }}
                                        <br>
                                        <strong>Positions Available:</strong> {{ $jobPosting->positions }}
                                        @if($jobPosting->pay_scale)
                                            <br><strong>Pay Scale:</strong> {{ $jobPosting->pay_scale }}
                                        @endif
                                        @if($jobPosting->job_type)
                                            <br><strong>Job Type:</strong> {{ ucwords(str_replace('_', ' ', $jobPosting->job_type)) }}
                                        @endif
                                        @if($jobPosting->gender && $jobPosting->gender != 'any')
                                            <br><strong>Gender Preference:</strong> {{ ucwords($jobPosting->gender) }}
                                        @endif
                                    </div>
                                </div>
                                 
                                 <div class="job-dates">
                                     <div class="date-item">
                                         <i class="fas fa-briefcase date-icon"></i>
                                         <span><strong>Opening Date:</strong> {{ $jobPosting->created_at->format('d-M-Y') }}</span>
                                     </div>
                                     <div class="date-item">
                                         <i class="fas fa-clock date-icon"></i>
                                         <span class="deadline"><strong>Last date to apply:</strong> {{ $jobPosting->deadline->format('d-M-Y') }}</span>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    @endforeach
                    <div class="d-flex justify-content-center">
            {{ $jobPostings->appends(['status' => request('status'), 'per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
        </div>

                @else
                    <div class="no-jobs">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h4>No job openings found</h4>
                        <p>Try adjusting your search criteria or check back later for new opportunities.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 