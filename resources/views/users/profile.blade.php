@extends('layouts.main')
@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-user mr-2"></i>My Profile
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('user.updateProfile') }}" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-user mr-2"></i>Personal Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           placeholder="Enter full name" 
                                           required 
                                           value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        National ID <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="cnic" 
                                           name="cnic" 
                                           class="form-control form-control-lg @error('cnic') is-invalid @enderror" 
                                           placeholder="38302-6327920-5" 
                                           required 
                                           value="{{ old('cnic', $user->cnic) }}"
                                           pattern="\d{5}-\d{7}-\d{1}"
                                           title="Format: XXXXX-XXXXXXX-X (e.g., 38302-6327920-5)">
                                    <small class="text-muted">Format: XXXXX-XXXXXXX-X</small>
                                    @error('cnic')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-phone mr-2"></i>Contact Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           placeholder="user@example.com" 
                                           required 
                                           value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                           placeholder="+923049972964" 
                                           required 
                                           value="{{ old('phone', $user->phone) }}"
                                           pattern="\+92\d{10}"
                                           title="Format: +92XXXXXXXXXX (e.g., +923049972964)">
                                    <small class="text-muted">Format: +92XXXXXXXXXX</small>
                                    @error('phone')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Organizational Information Section (Read Only) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-building mr-2"></i>Organizational Information
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle mr-2"></i>
                                    <strong>Note:</strong> Entity and Designation cannot be changed from here. Please contact your administrator for any changes.
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Entity
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg bg-light" 
                                           value="{{ $user->entity ? $user->entity->name : 'N/A' }}" 
                                           readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Designation
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg bg-light" 
                                           value="{{ $user->designation ? $user->designation->name : 'N/A' }}" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                
                                    <button type="submit" class="btn btn-lg" style="background-color: #00349C; color: white;">
                                        <i class="fa fa-save mr-2"></i>Update Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

