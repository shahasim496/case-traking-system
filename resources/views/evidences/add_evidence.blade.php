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
                    <option value="1">General</option>
                    <option value="d_exam">Dna Examination</option>
                    <option value="v_evidence">Questioned document</option>
                    <option value="ballisstics">Ballisstics</option>
                    <option value="currency">Currency</option>
                    <option value="toxicology">Toxicology</option>
                    <option value="v_evidence">video Evidence</option>
                </select>
            </div>

            <!-- general form -->
            <div id="formContainer" style="display: none;">
                <form method="POST" action="{{ route('evidence.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Submitted By Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">A. SUBMITTED BY:</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="submitted_by" class="form-label">ID</label>
                                    <input type="text" name="id" id="id" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="submitted_by" class="form-label">Full Name (Block Letters)</label>
                                    <input type="text" name="submitted_by" id="submitted_by" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="agency" class="form-label">Agency</label>
                                    <select name="agency" id="agency" class="form-control" required>
                                        <option value="" disabled selected>Select Agency</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="designation" class="form-label">Designation & Station</label>
                                    <input type="text" name="designation" id="designation" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact" class="form-label">Tel No.</label>
                                    <input type="text" name="contact" id="contact" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" id="address" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Case Details Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">B. CASE DETAILS:</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_incident" class="form-label">Date of Incident</label>
                                    <input type="date" name="date_of_incident" id="date_of_incident" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="offence" class="form-label">Offence</label>
                                    <input type="text" name="offence" id="offence" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" name="location" id="location" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="case_summary" class="form-label">Case Incident Summary</label>
                                    <textarea name="case_summary" id="case_summary" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit Evidence</button>
                    </div>
                </form>
            </div>
   <!--DNA examination -->


        </div>
    </div>
</div>


<!-- JavaScript to Toggle Form Visibility -->
<script>

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formSelector').addEventListener('change', function () {
        const formContainer = document.getElementById('formContainer');

        console.log(this.value); // Debugging line to check the selected value  
        if (this.value === '1' && formContainer.style.display === 'none') {
            formContainer.style.display = 'block';
        } else {
            formContainer.style.display = 'none';
        }
    });

});
</script>
@endsection

