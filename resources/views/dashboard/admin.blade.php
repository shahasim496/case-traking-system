@extends('layouts.admin_main')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-content-wrapper homepage">
    <div class="page-content form-main-2">
        <style>
            .card {
                border: none;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .card-title {
                font-size: 16px;
                font-weight: bold;
            }
            .color-theme {
                color: #2b7147;
            }
            .text-muted {
                font-size: 14px;
            }
            .btn-block {
                display: block;
                width: 100%;
                padding: 10px;
                font-size: 14px;
                font-weight: bold;
                text-align: center;
                border-radius: 4px;
            }
            .progress-container {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .progress-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .progress-label {
                flex: 1;
                font-weight: bold;
                color: #333;
            }
            .progress {
                flex: 3;
                height: 20px;
                background-color: #e9ecef;
                border-radius: 10px;
                overflow: hidden;
                margin: 0 15px;
            }
            .progress-bar {
                height: 100%;
                text-align: center;
                line-height: 20px;
                color: white;
                font-size: 12px;
            }
            .progress-value {
                flex: 1;
                text-align: right;
                font-weight: bold;
                color: #333;
            }
        </style>

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <h4 class="mt-1">Welcome to, <span class="color-theme bold">Dashboard</span></h4>
                <p class="text-muted">Here is an overview of the system's current status.</p>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row">
            <!-- Total Cases -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Cases</h5>
                        <h2 class="color-theme">{{$totalCases}}</h2>
                        <p class="text-muted">Cases in the system</p>
                    </div>
                </div>
            </div>

            <!-- Pending Cases -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Pending Cases</h5>
                        <h2 class="text-warning">{{$pendingCases}}</h2>
                        <p class="text-muted">Awaiting action</p>
                    </div>
                </div>
            </div>

            <!-- Resolved Cases -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Resolved Cases</h5>
                        <h2 class="text-success">{{$resolvedCases}}</h2>
                        <p class="text-muted">Successfully resolved</p>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="text-primary">{{$totalUsers}}</h2>
                        <p class="text-muted">Active users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Case Status Overview Section -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Case Status Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="progress-container">
                            <!-- Pending Cases -->
                            <div class="progress-item">
                                <span class="progress-label">Pending Verification Cases</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{$pendingPercentage}}%;" 
                                         aria-valuenow="{{$pendingPercentage}}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="progress-value">{{$pendingPercentage}}%</span>
                            </div>

                            <!-- Resolved Cases -->
                            <div class="progress-item">
                                <span class="progress-label">Resolved Cases</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{$resolvedPercentage}}%;" 
                                         aria-valuenow="{{$resolvedPercentage}}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="progress-value">{{$resolvedPercentage}}%</span>
                            </div>

                            <!-- Court Processing Cases -->
                            <div class="progress-item">
                                <span class="progress-label">Court Processing Cases</span>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" 
                                         style="width: {{$case_to_court_percentage}}%;" 
                                         aria-valuenow="{{$case_to_court_percentage}}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="progress-value">{{$case_to_court_percentage}}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
