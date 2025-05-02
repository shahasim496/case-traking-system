@extends('layouts.admin_main')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-content-wrapper homepage">
    <div class="page-content form-main-2">
        <style>
            .dashboard-cards {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* 3 cards per row */
                gap: 20px; /* Space between cards */
                margin-bottom: 20px;
            
                margin: 0 auto; /* Center the grid */
            }

            @media (max-width: 992px) {
                .dashboard-cards {
                    grid-template-columns: repeat(2, 1fr); /* 2 cards per row on tablets */
                }
            }

            @media (max-width: 576px) {
                .dashboard-cards {
                    grid-template-columns: repeat(1, 1fr); /* 1 card per row on mobile */
                }
            }

            .dashboard-card {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 20px;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .dashboard-card .icon {
                font-size: 32px;
                margin-bottom: 10px;
            }
            .dashboard-card h5 {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .dashboard-card h2 {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .dashboard-card p {
                font-size: 14px;
                color: #888;
                margin: 0;
            }
            .icon-blue { color: #007bff; }
            .icon-green { color: #28a745; }
            .icon-yellow { color: #ffc107; }
            .icon-red { color: #dc3545; }
            .icon-purple { color: #6f42c1; }
        </style>



        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <!-- Total Inmates -->
            <div class="dashboard-card">
                <div class="icon icon-blue">
                    <i class="fas fa-users"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Total Inmates</h5>
                <h2>10</h2> <!-- Replace with your data -->
                <p>2 Critical</p> <!-- Replace with your data -->
            </div>

            <!-- Active Assessments -->
            <div class="dashboard-card">
                <div class="icon icon-green">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Active Assessments</h5>
                <h2>5</h2> <!-- Replace with your data -->
                <p>1 Emergency</p> <!-- Replace with your data -->
            </div>

            <!-- Critical Cases -->
            <div class="dashboard-card">
                <div class="icon icon-yellow">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Critical Cases</h5>
                <h2>3</h2> <!-- Replace with your data -->
                <p>1 Needs Attention</p> <!-- Replace with your data -->
            </div>

            <!-- Active Medications -->
            <div class="dashboard-card">
                <div class="icon icon-purple">
                    <i class="fas fa-capsules"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Active Medications</h5>
                <h2>8</h2> <!-- Replace with your data -->
                <p>Total: 8</p> <!-- Replace with your data -->
            </div>

            <!-- Recent Incidents -->
            <div class="dashboard-card">
                <div class="icon icon-red">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Recent Incidents</h5>
                <h2>12</h2> <!-- Replace with your data -->
                <p>Total: 12</p> <!-- Replace with your data -->
            </div>

            <!-- Total Programs -->
            <div class="dashboard-card">
                <div class="icon icon-blue">
                    <i class="fas fa-list-alt"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Total Programs</h5>
                <h2>6</h2> <!-- Replace with your data -->
                <p>Total: 6</p> <!-- Replace with your data -->
            </div>
        </div>

        <!-- Recent Incidents Table -->
        <div class="recent-incidents">
            <h4 style="margin: 20px;">Recent Incidents</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CaseDepartmentName</th>
                    
             
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Cases as $case)
                    <tr>
                        <td>{{ $case->CaseID }}</td>
                        <td><span class="badge badge-danger">{{ $case->CaseDepartmentName}}</span></td>
                   
                      
                        <td><span class="badge badge-{{ strtolower($case->status) }}">{{ $case->CaseStatus }}</span></td>
                        <td>{{ $case->created_at->format('m/d/y, h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- New Section: Graphs -->
        <div class="graphs-section" style="margin-top: 40px;">
            <h4 style="margin-bottom: 20px;">Cases Statistics</h4>
            <div class="row">
                <!-- Incidents Over Time -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card" style="height: 400px;">
                        <div class="card-body">
                            <h5 class="card-title">Cases Over Time <i class="fas fa-info-circle" title="Monthly Incidents"></i></h5>
                            <canvas id="incidentsOverTime" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Incidents by Type -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card" style="height: 400px;">
                        <div class="card-body">
                            <h5 class="card-title">Cases By Percentage <i class="fas fa-info-circle" title=" Types"></i></h5>
                            <canvas id="incidentsByType" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Incidents by Severity -->
                <div class="col-lg-12 col-md-12">
                    <div class="card" style="height: 400px;">
                        <div class="card-body">
                            <h5 class="card-title">Cases By status <i class="fas fa-info-circle" title="Severity Levels"></i></h5>
                            <canvas id="incidentsBySeverity" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Incidents Over Time
            const incidentsOverTimeCtx = document.getElementById('incidentsOverTime').getContext('2d');
            new Chart(incidentsOverTimeCtx, {
                type: 'line',
                data: {
                    labels: ['closed', 'open', 'awating verification', 'court', 'released'],
                    datasets: [{
                        label: 'Monthly Incidents',
                        data: [4, 2, 1, 3, 1],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: { title: { display: true, text: 'Incident Types' } },
                        y: { title: { display: true, text: 'Count' }, beginAtZero: true }
                    }
                }
            });

            // Incidents by Type
            const incidentsByTypeCtx = document.getElementById('incidentsByType').getContext('2d');
            new Chart(incidentsByTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['closed', 'open', 'awating verification', 'court', 'released'],
                    datasets: [{
                        data: [60, 10, 10, 15, 5],
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#999999'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    }
                }
            });

            // Incidents by Severity
            const incidentsBySeverityCtx = document.getElementById('incidentsBySeverity').getContext('2d');
            new Chart(incidentsBySeverityCtx, {
                type: 'bar',
                data: {
                    labels: ['closed', 'open', 'awating verification'],
                    datasets: [{
                        label: 'Incidents by Severity',
                        data: [3, 1, 2],
                        backgroundColor: ['#ff6384', '#ffcd56', '#36a2eb'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: { title: { display: true, text: 'Severity Levels' } },
                        y: { title: { display: true, text: 'Count' }, beginAtZero: true }
                    }
                }
            });
        </script>
    </div>
</div>
@endsection
