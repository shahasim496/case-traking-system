@extends('layouts.admin_main')
@section('title', 'Evidence Dashboard')


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
        <h4 style="margin: 20px 0; padding: 10px; background-color: #007bff; color: white; border-radius: 5px;">Evidence Dashboard</h4>
        <div class="dashboard-cards">
            <!-- Total Evidence -->
            <div class="dashboard-card">
                <div class="icon icon-blue">
                    <i class="fas fa-archive"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Total Evidencess</h5>
                <h2>{{ $totalEvidence }}</h2>
            </div>

            <!-- Pending Evidence -->
            <div class="dashboard-card">
                <div class="icon icon-yellow">
                    <i class="fas fa-clock"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Pending Evidence</h5>
                <h2>{{ $pendingEvidence }}</h2>
            </div>

            <!-- Verified Evidence -->
            <div class="dashboard-card">
                <div class="icon icon-green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Verified Evidence</h5>
                <h2>{{ $verifiedEvidence }}</h2>
            </div>

            <!-- Completed Evidence -->
            <div class="dashboard-card">
                <div class="icon icon-purple">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Completed Evidence</h5>
                <h2>{{ $completedEvidence }}</h2>
            </div>

            <!-- DNA Evidence -->
            <div class="dashboard-card">
                <div class="icon icon-red">
                    <i class="fas fa-dna"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">DNA Evidence</h5>
                <h2>{{ $dnaEvidence }}</h2>
            </div>

            <!-- Ballistics Evidence -->
            <div class="dashboard-card">
                <div class="icon icon-blue">
                    <i class="fas fa-crosshairs"></i>
                </div>
                <h5 style="display: flex; justify-content: center;">Ballistics Evidence</h5>
                <h2>{{ $ballisticsEvidence }}</h2>
            </div>
        </div>

        <!-- Recent Evidence Table -->
        <div class="recent-evidence">
            <h4 style="margin: 20px; ">Recent Evidence Submissions</h4>
            <table class="table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Officer Name</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentEvidence as $evidence)
                    <tr>
                        <td>{{ $evidence->id }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($evidence->type) }}</span></td>
                        <td>{{ $evidence->officer_name }}</td>
                        <td>
                            <span class="badge badge-{{ $evidence->status == 'pending' ? 'danger' : ($evidence->status == 'verified' ? 'warning' : 'success') }}">
                                {{ ucfirst($evidence->status) }}
                            </span>
                        </td>
                        <td>{{ $evidence->created_at->format('m/d/y, h:i A') }}</td>
                        <!-- <td>
                            <a href="{{ route('evidence.show', $evidence->id) }}" class="btn btn-warning">View</a>
                            <a href="{{ route('evidence.receipt', $evidence->id) }}" class="btn btn-primary"   style="background-color:#007bff !important">Receipt</a>
                        </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- New Section: Graphs -->
        <div class="graphs-section" style="margin-top: 40px;">
            <h4 style="margin-bottom: 20px; padding: 10px; background-color: #007bff; color: white; border-radius: 5px;">Evidence Statistics</h4>
            <div class="row">
                <!-- Evidence By Type -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card" style="height: 400px;">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #007bff;"><i class="fas fa-chart-pie mr-2"></i> Evidence By Type</h5>
                            <canvas id="evidenceByType" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Evidence by Status -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card" style="height: 400px;">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #007bff;"><i class="fas fa-chart-bar mr-2"></i> Evidence By Status</h5>
                            <canvas id="evidenceByStatus" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Evidence By Type Chart
            const evidenceByTypeCtx = document.getElementById('evidenceByType').getContext('2d');
            new Chart(evidenceByTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['DNA', 'Ballistics', 'Currency', 'Toxicology', 'Video Evidence', 'Questioned Documents', 'General'],
                    datasets: [{
                        data: [
                            {{ $dnaEvidence }}, 
                            {{ $ballisticsEvidence }}, 
                            {{ $currencyEvidence }}, 
                            {{ $toxicologyEvidence }}, 
                            {{ $videoEvidence }}, 
                            {{ $questionedEvidence }}, 
                            {{ $generalEvidence }}
                        ],
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#c9cbcf', '#ff9f40'],
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

            // Evidence By Status Chart
            const evidenceByStatusCtx = document.getElementById('evidenceByStatus').getContext('2d');
            new Chart(evidenceByStatusCtx, {
                type: 'bar',
                data: {
                    labels: ['Pending', 'Verified', 'Completed'],
                    datasets: [{
                        label: 'Evidence Count',
                        data: [{{ $pendingEvidence }}, {{ $verifiedEvidence }}, {{ $completedEvidence }}],
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
                        x: { title: { display: true, text: 'Status' } },
                        y: { title: { display: true, text: 'Count' }, beginAtZero: true }
                    }
                }
            });
        </script>
    </div>
</div>
@endsection
