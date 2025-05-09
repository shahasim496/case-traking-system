@extends('layouts.main')
@section('title', 'Add Evidence')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header btn-primary text-white">
            <h4 class="mb-0">GUYANA FORENSIC SCIENCE LABORATORY</h4>
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
                    <option value="ballisstics">Ballisstics</option>
                    <option value="currency">Currency</option>
                    <option value="toxicology">Toxicology</option>
                    <option value="v_evidence">video Evidence</option>
                </select>
            </div>


        </div>

    </div>



    <!-- general form -->
    <div id="formContainer" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- CASE OFFICER / POINT OF CONTACT -->
            <div class="card mb-3">
                <div class="card-header bg-light">CASE OFFICER / POINT OF CONTACT</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                         <label>Id No #</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Full Name </label>
                            <input type="text" name="officer_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>

                    </div>
                </div>
            </div>

            <!-- SUPERVISOR / CERTIFYING OFFICER -->
            <div class="card mb-3">
                <div class="card-header bg-light">SUPERVISOR / CERTIFYING OFFICER</div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Id No#</label>
                            <input type="text" name="officer_id" class="form-control" required>
                        </div>


                        <div class="col-md-4 mb-3">
                            <label>Full Name label
                            </label>
                            <input type="text" name="supervisor_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>designation</label>
                            <input type="text" name="designation" class="form-control" required>
                        </div>


                    </div>
                </div>

                <!-- CASE DETAILS -->
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">B: CASE DETAILS</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Date Of Incident</label>
                                <input type="date" name="date_of_incident" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Offence</label>
                                <input type="text" name="offence" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Case Number</label>
                                <input type="text" name="case_number" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Case Incident Summary <small>(Please State Nature Of Suspect/Video Where Applicable)</small></label>
                                <textarea name="case_summary" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EVIDENCE SUBMITTED -->
                <div class="card mb-3">
                    <div class="card-header bg-light">C: EVIDENCE SUBMITTED</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Item ID#</label>
                                <input type="text" name="item_id" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Examination Required</label>
                                <input type="text" name="examination_required" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Description Of Package And Evidence</label>
                                <textarea name="evidence_description" class="form-control" rows="2" required></textarea>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success">+ ADD</button>
                    </div>
                </div>

                <!-- CHAIN OF CUSTODY -->
                <div class="card mb-3">
                    <div class="card-header bg-light">B: CHAIN OF CUSTODY</div>
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
                        <button type="button" class="btn btn-success">+ ADD</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit Evidence</button>
                </div>
        </form>
    </div>
    <!-- dna -->
    <div id="formContainer2" style="display: none;">
        <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Client (Requestor) Section -->
            <div class="card mb-3">
                <div class="card-header bg-light">Client (Requestor)</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="requestor_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Phone Number including Area Code <span class="text-danger">*</span></label>
                            <input type="text" name="requestor_phone" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Address <span class="text-danger">*</span></label>
                            <input type="text" name="requestor_address" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Signature <span class="text-danger">*</span></label>
                            <input type="text" name="requestor_signature" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="requestor_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donor Sections -->
            @for ($i = 1; $i <= 3; $i++)
                <div class="card mb-3">
                <div class="card-header bg-light">Donor #{{ $i }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="donor{{ $i }}_last_name" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" name="donor{{ $i }}_first_name" class="form-control" required>
                        </div>
                        <div class="col-md-1 mb-3">
                            <label>Middle Initial</label>
                            <input type="text" name="donor{{ $i }}_middle_initial" class="form-control">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Telephone Number</label>
                            <input type="text" name="donor{{ $i }}_phone" class="form-control">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Date of Birth</label>
                            <input type="date" name="donor{{ $i }}_dob" class="form-control">
                        </div>
                        <div class="col-md-1 mb-3">
                            <label>Gender</label>
                            <select name="donor{{ $i }}_gender" class="form-control">
                                <option value="M">M</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Address</label>
                            <input type="text" name="donor{{ $i }}_address" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Date and Time of Collection</label>
                            <input type="datetime-local" name="donor{{ $i }}_collection_datetime" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Donor Identification Number</label>
                            <input type="text" name="donor{{ $i }}_id_number" class="form-control">
                        </div>
                    </div>
                </div>
    </div>
    @endfor

    <!-- Result Reporting -->
    <div class="card mb-3">
        <div class="card-header bg-light">Result Reporting</div>
        <div class="card-body">
            <div class="mb-3">
                <label>Additional Copies Required For:</label>
                <div>
                    <label><input type="checkbox" name="report_donor1"> Donor #1</label>
                    <label><input type="checkbox" name="report_donor2"> Donor #2</label>
                    <label><input type="checkbox" name="report_donor3"> Donor #3</label>
                    <label><input type="checkbox" name="report_other"> Other</label>
                </div>
            </div>
            <div class="mb-3">
                <label>Additional Report For:</label>
                <input type="text" name="additional_report_for" class="form-control">
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="card mb-3">
        <div class="card-header bg-light">Payments</div>
        <div class="card-body">
            <div class="mb-3">
                <label>Payment Method:</label>
                <div>
                    <label><input type="checkbox" name="payment_cash"> Cash</label>
                    <label><input type="checkbox" name="payment_other"> Other</label>
                    <label><input type="checkbox" name="payment_na"> N/A</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Chain of Custody -->
    <div class="card mb-3">
        <div class="card-header bg-light">Chain of Custody</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label>Date</label>
                    <input type="date" name="chain_date" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label>Time</label>
                    <input type="time" name="chain_time" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Delivered By</label>
                    <input type="text" name="chain_delivered_by" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label>Received By</label>
                    <input type="text" name="chain_received_by" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Comments</label>
                    <input type="text" name="chain_comments" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Submit DNA Evidence</button>
    </div>
    </form>
</div>

<!-- Questioned Document Form -->
<div id="formContainer3" style="display: none;">
    <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Submitted By -->
        <div class="card mb-3">
            <div class="card-header bg-light">SUBMITTED BY</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Agency</label>
                        <input type="text" name="agency" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Division and Station</label>
                        <input type="text" name="division_station" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Tel No.</label>
                        <input type="text" name="tel_no" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Rank/Title</label>
                        <input type="text" name="submitted_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Full Name</label>
                        <input type="text" name="submitted_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Identification No.</label>
                        <input type="text" name="submitted_id" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Email</label>
                        <input type="email" name="submitted_email" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Case Officer - Rank/Title</label>
                        <input type="text" name="caseofficer_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Case Officer - Full Name</label>
                        <input type="text" name="caseofficer_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Supervisor - Rank/Title</label>
                        <input type="text" name="supervisor_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Supervisor - Full Name</label>
                        <input type="text" name="supervisor_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Supervisor - Signature</label>
                        <input type="text" name="supervisor_signature" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Case Details -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">CASE DETAILS</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Date of Incident</label>
                        <input type="date" name="date_of_incident" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Offence</label>
                        <input type="text" name="offence" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Case/Incident Summary</label>
                        <textarea name="case_summary" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Submitted -->
        <div class="card mb-3">
            <div class="card-header bg-light">EVIDENCE SUBMITTED</div>
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
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                <td><input type="text" name="item_id_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="item_desc_{{ $i }}" class="form-control"></td>
                                <td>
                                    <div>
                                        <label><input type="checkbox" name="item_submitted_{{ $i }}[]" value="Questioned Document"> Questioned Document</label><br>
                                        <label><input type="checkbox" name="item_submitted_{{ $i }}[]" value="Reference"> Reference</label>
                                    </div>
                                </td>
                                <td><input type="text" name="examination_requested_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chain of Custody -->
        <div class="card mb-3">
            <div class="card-header bg-light">CHAIN OF CUSTODY</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date (dd/mm/yy)</th>
                                <th>Time (24hr)</th>
                                <th>Delivered By</th>
                                <th>Received By</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 4; $i++)
                                <tr>
                                <td><input type="date" name="chain_date_{{ $i }}" class="form-control"></td>
                                <td><input type="time" name="chain_time_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="delivered_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="received_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="chain_comments_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit Questioned Document</button>
        </div>
    </form>
</div>

<!-- Ballistics Form -->
<div id="formContainer4" style="display: none;">
    <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Submitted By -->
        <div class="card mb-3">
            <div class="card-header bg-light">SUBMITTED BY</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Agency</label>
                        <input type="text" name="agency" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Division and Station</label>
                        <input type="text" name="division_station" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Tel No.</label>
                        <input type="text" name="tel_no" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Rank/Title</label>
                        <input type="text" name="submitted_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Full Name</label>
                        <input type="text" name="submitted_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Identification No.</label>
                        <input type="text" name="submitted_id" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Email</label>
                        <input type="email" name="submitted_email" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Case Details -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">CASE DETAILS</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Date of Incident</label>
                        <input type="date" name="date_of_incident" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Offence</label>
                        <input type="text" name="offence" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Case/Incident Summary</label>
                        <textarea name="case_summary" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Submitted -->
        <div class="card mb-3">
            <div class="card-header bg-light">EVIDENCE SUBMITTED</div>
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
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                <td><input type="text" name="item_id_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="item_desc_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="firearms_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="ammo_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="casings_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="bullets_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="examination_requested_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chain of Custody -->
        <div class="card mb-3">
            <div class="card-header bg-light">CHAIN OF CUSTODY</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>

                                <th>Time (24hr)</th>
                                <th>Delivered By</th>
                                <th>Received By</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 4; $i++)
                                <tr>
                                <td><input type="date" name="chain_date_{{ $i }}" class="form-control"></td>
                                <td><input type="time" name="chain_time_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="delivered_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="received_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="chain_comments_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit Ballistics Evidence</button>
        </div>
    </form>
</div>

<!-- Currency Form -->
<div id="formContainer5" style="display: none;">
    <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Submitted By -->
        <div class="card mb-3">
            <div class="card-header bg-light">SUBMITTED BY</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Agency</label>
                        <input type="text" name="agency" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Division and Station</label>
                        <input type="text" name="division_station" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Tel No.</label>
                        <input type="text" name="tel_no" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Rank/Title</label>
                        <input type="text" name="submitted_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Full Name</label>
                        <input type="text" name="submitted_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Identification No.</label>
                        <input type="text" name="submitted_id" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Email</label>
                        <input type="email" name="submitted_email" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Case Details -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">CASE DETAILS</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Date of Incident</label>
                        <input type="date" name="date_of_incident" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Offence</label>
                        <input type="text" name="offence" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Case/Incident Summary</label>
                        <textarea name="case_summary" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Submitted -->
        <div class="card mb-3">
            <div class="card-header bg-light">EVIDENCE SUBMITTED</div>
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
                            @for ($i = 1; $i <= 10; $i++)
                                <tr>
                                <td><input type="text" name="item_id_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="item_desc_{{ $i }}" class="form-control"></td>
                                <td>
                                    <select name="denomination_{{ $i }}" class="form-control">
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
                                <td><input type="number" name="quantity_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="subtotal_{{ $i }}" class="form-control" readonly></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <label>Total Value of Currency Received:</label>
                    <input type="number" name="total_value" class="form-control" readonly>
                </div>
            </div>
        </div>

        <!-- Chain of Custody -->
        <div class="card mb-3">
            <div class="card-header bg-light">CHAIN OF CUSTODY</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date (dd/mm/yy)</th>
                                <th>Time (24hr)</th>
                                <th>Delivered By</th>
                                <th>Received By</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 4; $i++)
                                <tr>
                                <td><input type="date" name="chain_date_{{ $i }}" class="form-control"></td>
                                <td><input type="time" name="chain_time_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="delivered_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="received_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="chain_comments_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit Currency Evidence</button>
        </div>
    </form>
</div>

<!-- Toxicology Form -->
<div id="formContainer6" style="display: none;">
    <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Submitted By -->
        <div class="card mb-3">
            <div class="card-header bg-light">SUBMITTED BY</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Agency</label>
                        <input type="text" name="agency" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Division and Station</label>
                        <input type="text" name="division_station" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Tel No.</label>
                        <input type="text" name="tel_no" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Rank/Title</label>
                        <input type="text" name="submitted_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Full Name</label>
                        <input type="text" name="submitted_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Identification No.</label>
                        <input type="text" name="submitted_id" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Email</label>
                        <input type="email" name="submitted_email" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Case Details -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">CASE DETAILS</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Date of Incident</label>
                        <input type="date" name="date_of_incident" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Offence</label>
                        <input type="text" name="offence" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Case/Incident Summary</label>
                        <textarea name="case_summary" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Details of Subject</label>
                        <input type="text" name="subject_details" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Sex</label>
                        <select name="sex" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Weight (kg)</label>
                        <input type="number" name="weight" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Height (cm)</label>
                        <input type="number" name="height" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Deceased">Deceased</option>
                            <option value="Coma">Coma</option>
                            <option value="Unconscious">Unconscious</option>
                            <option value="Hospitalized">Hospitalized</option>
                            <option value="Out Patient">Out Patient</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Submitted -->
        <div class="card mb-3">
            <div class="card-header bg-light">EVIDENCE SUBMITTED</div>
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
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                <td><input type="text" name="item_id_{{ $i }}" class="form-control"></td>
                                <td>
                                    <select name="sample_type_{{ $i }}" class="form-control">
                                        <option value="Central Blood">Central Blood</option>
                                        <option value="Peripheral Blood">Peripheral Blood</option>
                                        <option value="Gastric Content">Gastric Content</option>
                                        <option value="Urine">Urine</option>
                                        <option value="Liver">Liver</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                                <td><input type="number" name="quantity_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="collection_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="description_{{ $i }}" class="form-control"></td>
                                <td>
                                    <div>
                                        <label><input type="checkbox" name="examination_{{ $i }}[]" value="Alcohol/Volatile"> Alcohol/Volatile</label><br>
                                        <label><input type="checkbox" name="examination_{{ $i }}[]" value="Therapeutic Drug"> Therapeutic Drug</label><br>
                                        <label><input type="checkbox" name="examination_{{ $i }}[]" value="Heavy Metals"> Heavy Metals</label><br>
                                        <label><input type="checkbox" name="examination_{{ $i }}[]" value="Other"> Other</label>
                                    </div>
                                </td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chain of Custody -->
        <div class="card mb-3">
            <div class="card-header bg-light">CHAIN OF CUSTODY</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date (dd/mm/yy)</th>
                                <th>Time (24hr)</th>
                                <th>Delivered By</th>
                                <th>Received By</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 4; $i++)
                                <tr>
                                <td><input type="date" name="chain_date_{{ $i }}" class="form-control"></td>
                                <td><input type="time" name="chain_time_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="delivered_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="received_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="chain_comments_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit Toxicology Evidence</button>
        </div>
    </form>
</div>


<!-- Video Evidence Form -->
<div id="formContainer7" style="display: none;">
    <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Submitting Agency -->
        <div class="card mb-3">
            <div class="card-header bg-light">SUBMITTING AGENCY</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Agency</label>
                        <input type="text" name="agency" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Division/Station</label>
                        <input type="text" name="division_station" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Tel No.</label>
                        <input type="text" name="tel_no" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Rank/Title</label>
                        <input type="text" name="submitted_rank" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Submitted By - Full Name</label>
                        <input type="text" name="submitted_name" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>ID No.</label>
                        <input type="text" name="submitted_id" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Email</label>
                        <input type="email" name="submitted_email" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Case Details -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">CASE DETAILS</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Date of Incident</label>
                        <input type="date" name="date_of_incident" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Offence</label>
                        <input type="text" name="offence" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Description of Incident</label>
                        <textarea name="incident_description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Description of Object/Subject (Include vehicle, apparel & distinguishing marks)</label>
                        <textarea name="object_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Submitted -->
        <div class="card mb-3">
            <div class="card-header bg-light">EVIDENCE SUBMITTED</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
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
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                <td><input type="date" name="extraction_date_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="extracted_from_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="extraction_method_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="storage_media_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="retrieved_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="contact_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="num_cameras_{{ $i }}" class="form-control"></td>
                                <td><input type="number" name="num_videos_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="total_length_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chain of Custody -->
        <div class="card mb-3">
            <div class="card-header bg-light">CHAIN OF CUSTODY</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date (dd/mm/yy)</th>
                                <th>Time (24hr)</th>
                                <th>Delivered By</th>
                                <th>Received By</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 4; $i++)
                                <tr>
                                <td><input type="date" name="chain_date_{{ $i }}" class="form-control"></td>
                                <td><input type="time" name="chain_time_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="delivered_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="received_by_{{ $i }}" class="form-control"></td>
                                <td><input type="text" name="chain_comments_{{ $i }}" class="form-control"></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit Video Evidence</button>
        </div>
    </form>
</div>

</div>
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
@endsection