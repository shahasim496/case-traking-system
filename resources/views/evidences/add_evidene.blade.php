@extends('layouts.main')
@section('title', 'Add Evidence')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header  text-white" style="background-color: #0066cc;">
            <h4 class="mb-0">Evidence Management System</h4>
        </div>
        <div class="card-body">
            <!-- Dropdown to Select Option -->
            <div class="mb-4">
                <label for="formSelector" class="form-label">Select Option</label>
                <select id="formSelector" class="form-control">
                    <option value="" disabled selected>Select an Option</option>
                    <option value="general">General</option>
                    <option value="d_exam">Dna Examination</option>
                    <option value="q_document">Questioned document</option>
                    <option value="ballisstics">Ballistics</option>
                    <option value="currency">Currency</option>
                    <option value="toxicology">Toxicology</option>
                    <option value="v_evidence">Video Evidence</option>
                </select>
            </div>
        </div>
    </div>

    <!-- General Form -->
    <div id="formContainer" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="general">

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

              <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
</div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EVIDENCE SUBMITTED -->
            <div class="card mb-3">
                <div class="card-header bg-light">Evidence Submitted</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Item ID</label>
                            <input type="text" name="id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Description Of Package And Evidence</label>
                            <textarea name="evidence_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
              
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" style="background-color: #0066cc; color: white;">Submit Evidence</button>
            </div>
        </form>
    </div>

    <!-- Ballistics Form -->
    <div id="formContainer4" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="Ballistics">

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Submitted -->
            <div class="card mb-3">
                <div class="card-header bg-light">Evidence Submitted</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item ID</th>
                                    <th>Description of Package and Evidence</th>
                                    <th>No. of Firearms</th>
                                    <th>No. of Ammo</th>
                                    <th>No. of Casings</th>
                                    <th>No. of Bullets</th>
                                    <th>Examination Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="item_id" class="form-control"></td>
                                    <td><input type="text" name="item_desc_" class="form-control"></td>
                                    <td><input type="number" name="firearms_" class="form-control"></td>
                                    <td><input type="number" name="ammo_" class="form-control"></td>
                                    <td><input type="number" name="casings_" class="form-control"></td>
                                    <td><input type="number" name="bullets_" class="form-control"></td>
                                    <td><input type="text" name="examination_requested_" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                   
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" style="background-color: #0066cc; color: white;">Submit Ballistics Evidence</button>
            </div>
        </form>
    </div>

    <!-- Currency Form -->
    <div id="formContainer5" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="Currency">

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Submitted -->
            <div class="card mb-3">
                <div class="card-header bg-light">Evidence Submitted</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item ID</th>
                                    <th>Description of Package and Type of Currency Submitted</th>
                                    <th>Denomination</th>
                                    <th>Quantity</th>
                                    <th>Sub-Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="item_id" class="form-control"></td>
                                    <td><input type="text" name="item_desc_" class="form-control"></td>
                                    <td>
                                        <select name="denomination_" class="form-control">
                                            <option value="5000">5,000</option>
                                            <option value="2000">2,000</option>
                                            <option value="1000">1,000</option>
                                            <option value="500">500</option>
                                            <option value="100">100</option>
                                            <option value="50">50</option>
                                            <option value="20">20</option>
                                            <option value="10">10</option>
                                            <option value="5">5</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity_" class="form-control"></td>
                                    <td><input type="number" name="subtotal_" class="form-control" ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <label>Total Value of Currency Received:</label>
                        <input type="text" name="total_value" class="form-control" >
                    </div>
                </div>
            </div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
             
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" style="background-color: #0066cc; color: white;">Submit Currency Evidence</button>
            </div>
        </form>
    </div>

    <!-- Toxicology Form -->
    <div id="formContainer6" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="Toxicology">

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Submitted -->
            <div class="card mb-3">
                <div class="card-header bg-light">Evidence Submitted</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item ID</th>
                                    <th>Sample Type</th>
                                    <th>Quantity</th>
                                    <th>Collection</th>
                                    <th>Brief Description of Content</th>
                                    <th>Examination</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="item_id" class="form-control"></td>
                                    <td>
                                        <select name="sample_type" class="form-control">
                                            <option value="Central Blood">Central Blood</option>
                                            <option value="Peripheral Blood">Peripheral Blood</option>
                                            <option value="Gastric Content">Gastric Content</option>
                                            <option value="Urine">Urine</option>
                                            <option value="Liver">Liver</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity" class="form-control"></td>
                                    <td><input type="text" name="collection" class="form-control"></td>
                                    <td><input type="text" name="description" class="form-control"></td>
                                    <td>
                                        <div>
                                            <label><input type="checkbox" name="examination_1" value="Alcohol/Volatile"> Alcohol/Volatile</label><br>
                                            <label><input type="checkbox" name="examination_2" value="Therapeutic Drug"> Therapeutic Drug</label><br>
                                            <label><input type="checkbox" name="examination_3" value="Heavy Metals"> Heavy Metals</label><br>
                                            <label><input type="checkbox" name="examination_4" value="Other"> Other</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                  
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" style="background-color: #0066cc; color: white;">Submit Toxicology Evidence</button>
            </div>
        </form>
    </div>

    <!-- Video Evidence Form -->
    <div id="formContainer7" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="Video Evidence">

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

             <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Submitted -->
            <div class="card mb-3">
                <div class="card-header bg-light">Evidence Submitted</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>id</th>
                                    <th>Extraction Date</th>
                                    <th>Extracted From</th>
                                    <th>Extraction Method</th>
                                    <th>Storage Media</th>
                                    <th>Retrieved By</th>
                                    <th>Contact</th>
                                    <th>No. of Cameras</th>
                                    <th>No. of Videos</th>
                                    <th>Total Length</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="date" name="id" class="form-control"></td>
                                    <td><input type="date" name="extraction_date_" class="form-control"></td>
                                    <td><input type="text" name="extracted_from_" class="form-control"></td>
                                    <td><input type="text" name="extraction_method_" class="form-control"></td>
                                    <td><input type="text" name="storage_media_" class="form-control"></td>
                                    <td><input type="text" name="retrieved_by_" class="form-control"></td>
                                    <td><input type="text" name="contact_" class="form-control"></td>
                                    <td><input type="number" name="num_cameras_" class="form-control"></td>
                                    <td><input type="number" name="num_videos_" class="form-control"></td>
                                    <td><input type="text" name="total_length_" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
               
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" style="background-color: #0066cc; color: white;">Submit Video Evidence</button>
            </div>
        </form>
    </div>

    <!-- dna form -->
    <div id="formContainer2" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="type" value="dna"> <!-- Replace "dna" with the appropriate type -->

            <!-- police officer -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donor Sections -->
            <!-- Dropdown to Select Number of Donors -->
            <div class="mb-3">
                <label for="numDonors" class="form-label">Select Number of Donors</label>
                <select id="numDonors" class="form-control">
                    <option value="" disabled selected>Select Number of Donors</option>
                    @for ($i = 1; $i <= 10; $i++) <!-- Allow up to 10 donors -->
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Donor Sections -->
            <div id="donorSections"></div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                  
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" style="background-color: #0066cc; color: white;">Submit DNA Evidence</button>
            </div>
        </form>
    </div>

    <!-- questioned doc -->
    <div id="formContainer3" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="questioned">

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">Police Officer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Id No</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="email" name="officer_email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

              <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">GFSL Officer</div>
                <div class="card-body">
                <div class="row">
            <div class="col-md-4 mb-3">
                <label>Id No</label>
                <input type="text" name="g_officer_id" class="form-control" value="{{ auth()->user()->id }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" name="g_officer_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Designation</label>
                <input type="text" name="g_designation" class="form-control" value="{{ auth()->user()->designation->name ?? 'N/A' }}" readonly>
            </div>
        </div>
                </div>
            </div>

            <!-- Case Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">Case Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Case ID <span class="text-danger">*</span></label>
                            <input type="text" name="case_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Case Description <span class="text-danger">*</span></label>
                            <textarea name="case_description" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Submitted -->
            <div class="card mb-3">
                <div class="card-header bg-light">Evidence Submitted</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item ID</th>
                                    <th>Brief Description</th>
                                    <th>Item Submitted</th>
                                    <th>Examination Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="item_id_" class="form-control"></td>
                                    <td><input type="text" name="item_desc_" class="form-control"></td>
                                    <td>
                                        <div>
                                            <label><input type="checkbox" name="item_submitte" value="Questioned Document"> Questioned Document</label><br>
                                            <label><input type="checkbox" name="item_submitted" value="Reference"> Reference</label>
                                        </div>
                                    </td>
                                    <td><input type="text" name="examination_requested" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CHAIN OF CUSTODY -->
            <div class="card mb-3">
                <div class="card-header bg-light">Chain of Custody</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Date</label>
                            <input type="date" name="chain_date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Time</label>
                            <input type="time" name="chain_time" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Delivered By</label>
                            <input type="text" name="delivered_by" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Received By</label>
                            <input type="text" name="received_by" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comments</label>
                            <textarea name="chain_comments" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                 
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Submit Questioned Document</button>
            </div>
        </form>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#formSelector').on('change', function() {
            const selectedValue = $(this).val();

            // Hide all form containers first
            $('#formContainer').css('display', 'none');
            $('#formContainer2').css('display', 'none');
            $('#formContainer3').css('display', 'none');
            $('#formContainer4').css('display', 'none');
            $('#formContainer5').css('display', 'none');
            $('#formContainer6').css('display', 'none');
            $('#formContainer7').css('display', 'none');

            // Show the appropriate form
            if (selectedValue === 'general') {
                $('#formContainer').css('display', 'block');
            } else if (selectedValue === 'd_exam') {
                $('#formContainer2').css('display', 'block');
            } else if (selectedValue === 'q_document') {
                $('#formContainer3').css('display', 'block');
            } else if (selectedValue === 'ballisstics') {
                $('#formContainer4').css('display', 'block');
            } else if (selectedValue === 'currency') {
                $('#formContainer5').css('display', 'block');
            } else if (selectedValue === 'toxicology') {
                $('#formContainer6').css('display', 'block');
            } else if (selectedValue === 'v_evidence') {
                $('#formContainer7').css('display', 'block');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#numDonors').on('change', function() {
            var numDonors = parseInt($(this).val());
            var donorSections = '';
            for (var i = 1; i <= numDonors; i++) {
                donorSections += `
                <div class="card mb-3">
                    <div class="card-header bg-light">Donor #${i}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label>Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="donor${i}_last_name" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input type="text" name="donor${i}_first_name" class="form-control" required>
                            </div>
                            <div class="col-md-1 mb-3">
                                <label>Middle Initial</label>
                                <input type="text" name="donor${i}_middle_initial" class="form-control">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Telephone Number</label>
                                <input type="text" name="donor${i}_phone" class="form-control">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Date of Birth</label>
                                <input type="date" name="donor${i}_dob" class="form-control">
                            </div>
                            <div class="col-md-1 mb-3">
                                <label>Gender</label>
                                <select name="donor${i}_gender" class="form-control">
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Address</label>
                                <input type="text" name="donor${i}_address" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Date and Time of Collection</label>
                                <input type="datetime-local" name="donor${i}_collection_datetime" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Donor Identification Number</label>
                                <input type="text" name="donor${i}_id_number" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                `;
            }
            $('#donorSections').html(donorSections);
        });
    });
</script>
@endsection