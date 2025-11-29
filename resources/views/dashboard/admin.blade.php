@extends('layouts.admin_main')
@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    @include('components.toaster')
    
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fa fa-tachometer mr-2" style="color: #00349C;"></i>Dashboard
            </h2>
            <p class="text-muted">Court Case Tracking System Overview</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Cases</h6>
                            <h3 class="mb-0" style="color: #00349C;">{{ $totalCases }}</h3>
                        </div>
                        <div class="icon-circle bg-primary text-white">
                            <i class="fa fa-gavel fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Open Cases</h6>
                            <h3 class="mb-0" style="color: #28a745;">{{ $openCases }}</h3>
                        </div>
                        <div class="icon-circle bg-success text-white">
                            <i class="fa fa-folder-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-left-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Closed Cases</h6>
                            <h3 class="mb-0" style="color: #dc3545;">{{ $closedCases }}</h3>
                        </div>
                        <div class="icon-circle bg-danger text-white">
                            <i class="fa fa-folder fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Notices</h6>
                            <h3 class="mb-0" style="color: #17a2b8;">{{ $totalNotices }}</h3>
                        </div>
                        <div class="icon-circle bg-info text-white">
                            <i class="fa fa-file-text fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Case Status Distribution (Pie Chart) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-pie-chart mr-2"></i>Case Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Court Type Distribution (Doughnut Chart) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-chart-pie mr-2"></i>Court Type Distribution
                    </h5>
                </div>
                <div class="card-body" >
                    <canvas id="courtTypeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends and Department Cases -->
    <div class="row mb-4">
        <!-- Monthly Case Trends (Line Chart) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-line-chart mr-2"></i>Monthly Case Trends (Last 6 Months)
                    </h5>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="monthlyTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Department-wise Cases (Bar Chart) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                    <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-bar-chart mr-2"></i>Cases by Department
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="departmentChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Row -->
    <div class="row">
        <!-- Recent Cases -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-list mr-2"></i>Recent Cases
                    </h5>
                    <a href="{{ route('cases.index') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-eye"></i> View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>Case #</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCases as $case)
                                    <tr>
                                        <td>
                                            <a href="{{ route('cases.show', $case->id) }}" class="text-primary">
                                                <strong>{{ $case->case_number }}</strong>
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($case->case_title, 25) }}</td>
                                        <td>
                                            @if($case->status == 'Open')
                                                <span class="badge badge-success">Open</span>
                                            @else
                                                <span class="badge badge-danger">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No cases found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Hearings -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-calendar-check-o mr-2"></i>Upcoming Hearings (Next 7 Days)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>Date</th>
                                    <th>Case</th>
                                    <th>Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingHearings as $hearing)
                                    <tr>
                                        <td>
                                            <strong>{{ $hearing->hearing_date->format('d M') }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('cases.show', $hearing->case_id) }}" class="text-primary">
                                                {{ Str::limit($hearing->courtCase->case_number, 15) }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($hearing->purpose, 20) ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No upcoming hearings</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notices -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h5 class="mb-0">
                        <i class="fa fa-bell mr-2"></i>Recent Notices
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>Date</th>
                                    <th>Case</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentNotices as $notice)
                                    <tr>
                                        <td>
                                            <strong>{{ $notice->notice_date->format('d M') }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('cases.show', $notice->case_id) }}" class="text-primary">
                                                {{ Str::limit($notice->courtCase->case_number, 15) }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($notice->notice_details, 20) ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No recent notices</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Container Fluid - Only adjust padding, let existing CSS handle page-content margins */
.container-fluid {
    padding-left: 15px;
    padding-right: 15px;
}

@media (max-width: 991px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
}

/* Card Styles */
.border-left-primary {
    border-left: 4px solid #00349C !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 576px) {
    .icon-circle {
        width: 50px;
        height: 50px;
    }
    
    .icon-circle i {
        font-size: 1.5rem !important;
    }
}

.card {
    border: none;
    border-radius: 10px;
    transition: transform 0.2s;
    margin-bottom: 1rem;
}

.card:hover {
    transform: translateY(-5px);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.card-body {
    padding: 1.25rem;
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
    }
}

/* Responsive Charts */
@media (max-width: 768px) {
    #statusChart,
    #courtTypeChart,
    #monthlyTrendChart,
    #departmentChart {
        max-height: 250px !important;
    }
}

@media (max-width: 576px) {
    #statusChart,
    #courtTypeChart,
    #monthlyTrendChart,
    #departmentChart {
        max-height: 200px !important;
    }
}

/* Statistics Cards Responsive */
@media (max-width: 768px) {
    .col-lg-3.col-md-6 {
        margin-bottom: 1rem;
    }
    
    h3.mb-0 {
        font-size: 1.75rem;
    }
}

@media (max-width: 576px) {
    h3.mb-0 {
        font-size: 1.5rem;
    }
    
    h6.text-muted {
        font-size: 0.875rem;
    }
}

/* Page Header Responsive */
@media (max-width: 768px) {
    h2.mb-0 {
        font-size: 1.5rem;
    }
    
    .text-muted {
        font-size: 0.875rem;
    }
}

/* Table Responsive */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

@media (max-width: 768px) {
    .table-responsive {
        max-height: 350px !important;
    }
}

/* Chart Container Responsive */
@media (max-width: 992px) {
    .col-lg-6,
    .col-lg-8,
    .col-lg-4 {
        margin-bottom: 1.5rem;
    }
}

/* Ensure proper spacing on mobile */
@media (max-width: 576px) {
    .row {
        margin-left: -5px;
        margin-right: -5px;
    }
    
    .row > [class*="col-"] {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .mb-4 {
        margin-bottom: 1rem !important;
    }
    
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
}

/* Mobile Responsive - Let the existing CSS handle page-content margins */
@media (max-width: 991px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
}
</style>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
// Case Status Distribution (Pie Chart)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Open Cases', 'Closed Cases'],
        datasets: [{
            data: [{{ $openCases }}, {{ $closedCases }}],
            backgroundColor: ['#28a745', '#dc3545'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.parsed + ' cases';
                        return label;
                    }
                }
            }
        }
    }
});

// Court Type Distribution (Doughnut Chart)
const courtTypeCtx = document.getElementById('courtTypeChart').getContext('2d');
const courtTypeData = {
    labels: {!! json_encode(array_keys($courtTypeDistribution)) !!},
    datasets: [{
        data: {!! json_encode(array_values($courtTypeDistribution)) !!},
        backgroundColor: ['#00349C', '#28a745', '#ffc107'],
        borderWidth: 2,
        borderColor: '#fff'
    }]
};
const courtTypeChart = new Chart(courtTypeCtx, {
    type: 'doughnut',
    data: courtTypeData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.parsed + ' cases';
                        return label;
                    }
                }
            }
        }
    }
});

// Monthly Trends (Line Chart)
const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyTrends, 'month')) !!},
        datasets: [{
            label: 'Cases Added',
            data: {!! json_encode(array_column($monthlyTrends, 'count')) !!},
            borderColor: '#17a2b8',
            backgroundColor: 'rgba(23, 162, 184, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#17a2b8',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Cases: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Department-wise Cases (Bar Chart)
const deptCtx = document.getElementById('departmentChart').getContext('2d');
const deptData = {!! json_encode($departmentCases) !!};
const departmentChart = new Chart(deptCtx, {
    type: 'bar',
    data: {
        labels: deptData.map(item => item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name),
        datasets: [{
            label: 'Cases',
            data: deptData.map(item => item.total),
            backgroundColor: '#ffc107',
            borderColor: '#ff9800',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Cases: ' + context.parsed.x;
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endsection

