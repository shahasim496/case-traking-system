@extends('layouts.admin_main')
@section('title', 'Dashboard')

@section('content')
<style>
    /* Dashboard specific styles - ensure proper spacing with sidebar */
    /* Note: margin-left: 235px is set by layout CSS for sidebar spacing - don't override it */
    .page-content-wrapper.homepage .page-content {
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-top: 15px !important;
    }
    
    /* Ensure sidebar doesn't overlap content */
    .sidemenu-container {
        width: 235px !important;
    }
    
    /* Modern Dashboard Styles */
            .dashboard-header {
                background: linear-gradient(135deg, #00349C 0%, #0056b3 100%);
                color: white;
                padding: 30px;
                border-radius: 15px;
                margin-bottom: 30px;
                box-shadow: 0 8px 25px rgba(0, 52, 156, 0.3);
            }

            .dashboard-header h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 10px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            }

            .dashboard-header p {
                font-size: 1.1rem;
                opacity: 0.9;
                margin: 0;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 25px;
                margin-bottom: 40px;
            }

            .stat-card {
                background: #fff;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                border: 1px solid rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
            }

            .stat-card.blue { --card-color: #007bff; --card-color-light: #66b3ff; }
            .stat-card.green { --card-color: #28a745; --card-color-light: #6bcf7f; }
            .stat-card.yellow { --card-color: #ffc107; --card-color-light: #ffd43b; }
            .stat-card.red { --card-color: #dc3545; --card-color-light: #ff6b7a; }
            .stat-card.purple { --card-color: #6f42c1; --card-color-light: #9d71d9; }
            .stat-card.orange { --card-color: #fd7e14; --card-color-light: #ffa94d; }

            .stat-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: white;
                background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
            }

            .stat-trend {
                display: flex;
                align-items: center;
                font-size: 14px;
                font-weight: 600;
            }

            .stat-trend.up { color: #28a745; }
            .stat-trend.down { color: #dc3545; }

            .stat-value {
                font-size: 2.5rem;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 5px;
                line-height: 1;
            }

            .stat-label {
                font-size: 1rem;
                color: #6c757d;
                font-weight: 500;
                margin: 0;
            }

            .stat-description {
                font-size: 0.875rem;
                color: #adb5bd;
                margin-top: 8px;
            }

            .charts-section {
                background: #fff;
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                margin-bottom: 30px;
            }

            .section-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 25px;
                display: flex;
                align-items: center;
            }

            .section-title i {
                margin-right: 10px;
                color: #00349C;
            }

            .chart-container {
                position: relative;
                height: 400px;
                margin-bottom: 30px;
            }

            .recent-cases-section {
                background: #fff;
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            }

            .table-modern {
                background: #fff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .table-modern thead {
                background: linear-gradient(135deg, #00349C 0%, #0056b3 100%);
                color: white;
            }

            .table-modern thead th {
                border: none;
                padding: 20px 15px;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.875rem;
                letter-spacing: 0.5px;
            }

            .table-modern tbody td {
                padding: 15px;
                border-bottom: 1px solid #f8f9fa;
                vertical-align: middle;
            }

            .table-modern tbody tr:hover {
                background-color: #f8f9fa;
            }

            .badge-modern {
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .badge-pending { background: #fff3cd; color: #856404; }
            .badge-resolved { background: #d4edda; color: #155724; }
            .badge-closed { background: #f8d7da; color: #721c24; }
            .badge-court { background: #d1ecf1; color: #0c5460; }

            .pagination-modern {
                display: flex;
                justify-content: center;
                margin-top: 25px;
            }

            .pagination-modern .page-link {
                border: none;
                color: #00349C;
                padding: 10px 15px;
                margin: 0 2px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .pagination-modern .page-link:hover {
                background: #00349C;
                color: white;
            }

            .pagination-modern .page-item.active .page-link {
                background: #00349C;
                color: white;
            }

            /* Responsive Design */
            @media (max-width: 1200px) {
                .stats-grid {
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                }
            }

            @media (max-width: 992px) {
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                }
            }

            @media (max-width: 768px) {
                .dashboard-header {
                    padding: 20px;
                    text-align: center;
                }

                .dashboard-header h1 {
                    font-size: 2rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }

                .stat-card {
                    padding: 20px;
                }

                .stat-value {
                    font-size: 2rem;
                }

                .charts-section,
                .recent-cases-section {
                    padding: 20px;
                }

                .chart-container {
                    height: 300px;
                }
            }

            @media (max-width: 576px) {
                .stat-card-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 15px;
                }

                .stat-icon {
                    width: 50px;
                    height: 50px;
                    font-size: 20px;
                }

                .stat-value {
                    font-size: 1.75rem;
                }

                .table-modern {
                    font-size: 0.875rem;
                }

                .table-modern thead th,
                .table-modern tbody td {
                    padding: 10px 8px;
                }
            }
        </style>

<div class="container-fluid" style="padding: 0 20px;">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Overview</h1>
            <p>Welcome to the Case Management System - Monitor and track all case activities</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <!-- Total Users -->
            <div class="stat-card blue">
                <div class="stat-card-header">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>Active</span>
                    </div>
                </div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <p class="stat-label">Total Users</p>
                <p class="stat-description">Registered system users</p>
            </div>

            <!-- Total Cases -->
            <div class="stat-card purple">
                <div class="stat-card-header">
                    <div class="stat-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>{{ $totalCases > 0 ? '100%' : '0%' }}</span>
                    </div>
                </div>
                <div class="stat-value">{{ $totalCases }}</div>
                <p class="stat-label">Total Cases</p>
                <p class="stat-description">All case records</p>
            </div>

            <!-- Pending Cases -->
            <div class="stat-card yellow">
                <div class="stat-card-header">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-percentage"></i>
                        <span>{{ $pendingPercentage }}%</span>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingCases }}</div>
                <p class="stat-label">Pending Cases</p>
                <p class="stat-description">Awaiting verification</p>
            </div>

            <!-- Resolved Cases -->
            <div class="stat-card green">
                <div class="stat-card-header">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        <span>{{ $resolvedPercentage }}%</span>
                    </div>
                </div>
                <div class="stat-value">{{ $resolvedCases }}</div>
                <p class="stat-label">Resolved Cases</p>
                <p class="stat-description">Successfully resolved</p>
            </div>

            <!-- Court Cases -->
            <div class="stat-card red">
                <div class="stat-card-header">
                    <div class="stat-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-percentage"></i>
                        <span>{{ $case_to_court_percentage }}%</span>
                    </div>
                </div>
                <div class="stat-value">{{ $case_to_court }}</div>
                <p class="stat-label">Court Cases</p>
                <p class="stat-description">Approved for court</p>
                
                @if(!empty($topCourts))
                <div class="mt-3 pt-3" style="border-top: 1px solid rgba(0,0,0,0.1);">
                    @foreach($topCourts as $court)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size: 0.875rem; color: #6c757d;">{{ $court['name'] }}</span>
                        <span style="font-size: 0.875rem; font-weight: 600; color: #2c3e50;">{{ $court['total'] }} cases</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Closed Cases -->
            <div class="stat-card orange">
                <div class="stat-card-header">
                    <div class="stat-icon">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-lock"></i>
                        <span>Closed</span>
                    </div>
                </div>
                <div class="stat-value">{{ $ClosedCases }}</div>
                <p class="stat-label">Closed Cases</p>
                <p class="stat-description">Archived cases</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <h2 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Case Analytics & Statistics
            </h2>
            
            <div class="row">
                <!-- Cases by Entity -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="chart-container">
                        <h5 class="mb-3"><i class="fas fa-pie-chart"></i> Cases by Entity</h5>
                        <canvas id="incidentsByType"></canvas>
                    </div>
                </div>

                <!-- Cases by Court -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="chart-container">
                        <h5 class="mb-3"><i class="fas fa-chart-column"></i> Cases by Court</h5>
                        <canvas id="incidentsBySeverity"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends -->
            <div class="row">
                <div class="col-12">
                    <div class="chart-container">
                        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Monthly Case Trends</h5>
                        <canvas id="monthlyCasesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Cases Section -->
        <div class="recent-cases-section">
            <h2 class="section-title">
                <i class="fas fa-list-alt"></i>
                Recent Cases
            </h2>
            
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> Case Number</th>
                            <th><i class="fas fa-building"></i> Entity</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-calendar"></i> Created Date</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($Cases as $case)
                        <tr>
                            <td>
                                <strong>#{{ $case->case_number }}</strong>
                            </td>
                            <td>
                                <span class="badge-modern badge-pending">{{ $case->entity->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = 'badge-pending';
                                    if ($case->status == 'Open') {
                                        $statusClass = 'badge-pending';
                                    } elseif ($case->status == 'Resolved') {
                                        $statusClass = 'badge-resolved';
                                    } else {
                                        $statusClass = 'badge-closed';
                                    }
                                @endphp
                                <span class="badge-modern {{ $statusClass }}">{{ $case->status }}</span>
                            </td>
                            <td>
                                <i class="fas fa-clock text-muted"></i>
                                {{ $case->created_at->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $case->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if(auth()->user()->can('view case'))
                                <a href="{{ route('cases.show', $case->id) }}" 
                                   class="btn btn-sm d-flex align-items-center justify-content-center" 
                                   style="width: 80px; background-color: #17a2b8; color: white;"
                                   title="View Case">
                                    <i class="fa fa-eye mr-1"></i>View
                                </a>
                                @else
                                <span class="text-muted">No Access</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No cases found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($Cases->hasPages())
            <div class="pagination-modern">
                {{ $Cases->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
</div>

        <!-- Include Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Pass PHP data to JS
            const casesByCourt = @json($casesByCourt);
            const casesByType = @json($casesByType);
            const monthlyCases = @json($monthlyCases);

            // Chart.js default configuration
            Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#6c757d';

            // Modern color palette
            const colors = {
                primary: '#00349C',
                secondary: '#0056b3',
                success: '#28a745',
                warning: '#ffc107',
                danger: '#dc3545',
                info: '#17a2b8',
                purple: '#6f42c1',
                orange: '#fd7e14'
            };

            // Monthly Cases Chart
            const monthlyCasesCtx = document.getElementById('monthlyCasesChart').getContext('2d');
            
            // Debug: Log the monthly cases data
            console.log('Monthly Cases Data:', monthlyCases);
            
            // Ensure we have data, if not create empty structure
            const chartData = monthlyCases && monthlyCases.length > 0 ? monthlyCases : [];
            
            // If no data, show a message
            if (chartData.length === 0) {
                console.log('No monthly data available');
                // You could show a "No data available" message here
            }
            
            new Chart(monthlyCasesCtx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => {
                        const [year, month] = item.month.split('-');
                        return new Date(parseInt(year), parseInt(month) - 1).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                    }),
                    datasets: [
                        {
                            label: 'Total Cases',
                            data: chartData.map(item => item.total_cases || 0),
                            borderColor: colors.primary,
                            backgroundColor: `${colors.primary}15`,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: colors.primary,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Open Cases',
                            data: chartData.map(item => item.pending_cases || 0),
                            borderColor: colors.warning,
                            backgroundColor: `${colors.warning}15`,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: colors.warning,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Under Investigation',
                            data: chartData.map(item => item.resolved_cases || 0),
                            borderColor: colors.success,
                            backgroundColor: `${colors.success}15`,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: colors.success,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Pending Court',
                            data: chartData.map(item => item.court_cases || 0),
                            borderColor: colors.danger,
                            backgroundColor: `${colors.danger}15`,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: colors.danger,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: colors.primary,
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' cases';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12
                                }
                            },
                            title: {
                                display: true,
                                text: 'Number of Cases',
                                color: '#495057',
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12
                                }
                            },
                            title: {
                                display: true,
                                text: 'Month',
                                color: '#495057',
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        }
                    }
                }
            });

            // Cases by Entity (Doughnut Chart)
            const incidentsByTypeCtx = document.getElementById('incidentsByType').getContext('2d');
            new Chart(incidentsByTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(casesByType),
                    datasets: [{
                        data: Object.values(casesByType),
                        backgroundColor: [
                            colors.primary,
                            colors.secondary,
                            colors.success,
                            colors.warning,
                            colors.danger,
                            colors.info,
                            colors.purple,
                            colors.orange
                        ],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: colors.primary,
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' cases (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Cases by Court (Bar Chart)
            const incidentsBySeverityCtx = document.getElementById('incidentsBySeverity').getContext('2d');
            new Chart(incidentsBySeverityCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(casesByCourt),
                    datasets: [{
                        data: Object.values(casesByCourt),
                        backgroundColor: [
                            colors.warning,
                            colors.success,
                            colors.danger,
                            colors.info,
                            colors.primary,
                            colors.purple,
                            colors.orange
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 60
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: colors.primary,
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' cases';
                                }
                            }
                        }
                    },
                    scales: {
                        x: { 
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: { 
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });

            // Add animation to stat cards on page load
            document.addEventListener('DOMContentLoaded', function() {
                const statCards = document.querySelectorAll('.stat-card');
                statCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.6s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
@endsection
