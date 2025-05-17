@extends('layouts.main')
@section('title', 'Add Evidence')

@section('content')
<div class="container-fluid mt-4">
   


            <!-- general form -->
            <div id="formContainer" >
                <form method="POST" action="{{ route('evidence.verifyPoliceOfficer') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Submitted By Section -->
                    <div class="card mb-4">
                        <div style="background-color: #0066cc;" class="card-header text-white">SUBMITTED BY:</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id" class="form-label">ID</label>
                                    <input type="text" name="id" id="id" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="submitted_by" class="form-label">Full Name</label>
                                    <input type="text" name="submitted_by" id="submitted_by" class="form-control" required>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="agency" class="form-label"> Department/Agency</label>
                                    <select name="agency" id="agency" class="form-control" required>
                                        <option value="" disabled selected>Select Agency</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="designation" class="form-label"> Designation</label>
                                    <select name="designation" id="designation" class="form-control" required>
                                        <option value="" disabled selected>Select designation</option>
                                        @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                

                               

                                <div class="col-md-6 mb-3">
                                    <label for="contact" class="form-label">Contact No.</label>
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
                    

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background-color: #0066cc; color: white;">Verify Officer</button>
                    </div>
                </form>
            </div>
   <!--DNA examination -->


</div>



@endsection

