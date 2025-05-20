@extends('layouts.main')
@section('title', 'Evidence Submission Receipt')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center  text-white" style="background-color: #0066cc;">
            <h4 class="mb-0">Evidence Submission Receipt</h4>
            <button onclick="window.print()" class="btn btn-light"><i class="fas fa-print"></i> Print Receipt</button>
        </div>
     

            <!-- Receipt Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Evidence Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Type</th>
                                        <td>{{ ucfirst($evidence->type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference Number</th>
                                        <td>GFSL-{{ date('Y') }}-{{ $evidence->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Submission Date</th>
                                        <td>{{ $evidence->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-warning"> {{$evidence->status}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Officer Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Officer ID</th>
                                        <td>{{ $evidence->officer_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Officer Name</th>
                                        <td>{{ $evidence->officer_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Designation</th>
                                        <td>{{ $evidence->designation }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $evidence->officer_email }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">GFSL Receipt Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">GFSL Officer ID</th>
                                        <td>{{ $evidence->g_officer_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>GFSL Officer Name</th>
                                        <td>{{ $evidence->g_officer_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>GFSL Designation</th>
                                        <td>{{ $evidence->g_designation }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 text-center">
                                <p class="mb-1">This is a digital receipt for evidence submission.</p>
                                <p class="mb-1">A verification email has been sent to: {{ $evidence->officer_email }}</p>
                                <div class="mt-3">
                                    <div class="border border-secondary p-2 d-inline-block">
                                        <h4 class="mb-0">GFSL-{{ date('Y') }}-{{ $evidence->id }}</h4>
                                        <small>Reference Number</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('evidences.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('evidence.show', $evidence->id) }}" style="background-color: #0066cc; color: white;" class="btn">View Full Details</a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .alert, nav, footer, .actions {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 1px solid #ddd !important;
        }
    }
</style>
@endsection 