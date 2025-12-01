@extends('layouts.main')
@section('title', 'Edit Case')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-edit mr-2"></i>Edit Case
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <form method="POST" action="{{ route('cases.update', $case->id) }}" id="caseForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Case Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-gavel mr-2"></i>Case Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Case Type <span class="text-danger">*</span>
                                    </label>
                                    <select id="case_type_id" 
                                            name="case_type_id" 
                                            class="form-control form-control-lg @error('case_type_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Case Type</option>
                                        @foreach($caseTypes as $caseType)
                                            <option value="{{ $caseType->id }}" {{ old('case_type_id', $case->case_type_id) == $caseType->id ? 'selected' : '' }}>
                                                {{ $caseType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('case_type_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Case Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="case_number" 
                                           name="case_number" 
                                           class="form-control form-control-lg @error('case_number') is-invalid @enderror" 
                                           placeholder="Enter case number" 
                                           required 
                                           value="{{ old('case_number', $case->case_number) }}">
                                    @error('case_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Court Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-balance-scale mr-2"></i>Court Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Court <span class="text-danger">*</span>
                                    </label>
                                    <select id="court_id" 
                                            name="court_id" 
                                            class="form-control form-control-lg @error('court_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Court</option>
                                        @foreach($courts as $court)
                                            <option value="{{ $court->id }}" {{ old('court_id', $case->court_id) == $court->id ? 'selected' : '' }}>
                                                {{ $court->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('court_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6" id="benchField" style="display: {{ ($case->court && in_array($case->court->court_type, ['High Court', 'Supreme Court'])) ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Bench <span class="text-danger" id="benchRequired">*</span>
                                    </label>
                                    <select id="work_bench_id" 
                                            name="work_bench_id" 
                                            class="form-control form-control-lg @error('work_bench_id') is-invalid @enderror">
                                        <option value="">Select Bench</option>
                                        @if($case->court && in_array($case->court->court_type, ['High Court', 'Supreme Court']))
                                            @foreach($case->court->workBenches as $bench)
                                                <option value="{{ $bench->id }}" {{ old('work_bench_id', $case->work_bench_id) == $bench->id ? 'selected' : '' }}>
                                                    {{ $bench->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('work_bench_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6" id="judgeField" style="display: {{ ($case->court && $case->court->court_type == 'Session Court') ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Judge Name <span class="text-danger" id="judgeRequired" style="display: {{ ($case->court && $case->court->court_type == 'Session Court') ? 'inline' : 'none' }};">*</span>
                                    </label>
                                    <input type="text" 
                                           id="judge_name" 
                                           name="judge_name" 
                                           class="form-control form-control-lg @error('judge_name') is-invalid @enderror" 
                                           placeholder="Enter judge name" 
                                           value="{{ old('judge_name', $case->judge_name ?? '') }}">
                                    @error('judge_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Remarks
                                    </label>
                                    <textarea id="remarks" 
                                              name="remarks" 
                                              class="form-control form-control-lg @error('remarks') is-invalid @enderror" 
                                              rows="3" 
                                              placeholder="Enter remarks...">{{ old('remarks', $case->remarks) }}</textarea>
                                    @error('remarks')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Case Details Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-info-circle mr-2"></i>Case Details
                                </h5>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Case Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="case_title" 
                                           name="case_title" 
                                           class="form-control form-control-lg @error('case_title') is-invalid @enderror" 
                                           placeholder="Enter case title" 
                                           required 
                                           value="{{ old('case_title', $case->case_title) }}">
                                    @error('case_title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Case Description
                                    </label>
                                    <textarea id="case_description" 
                                              name="case_description" 
                                              class="form-control form-control-lg @error('case_description') is-invalid @enderror" 
                                              rows="4" 
                                              placeholder="Enter case description...">{{ old('case_description', $case->case_description) }}</textarea>
                                    @error('case_description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Existing Files Section -->
                        @if($case->caseFiles->count() > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-file mr-2"></i>Existing Files
                                </h5>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Original Name</th>
                                                <th>Size</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($case->caseFiles as $file)
                                            <tr>
                                                <td>{{ $file->file_name }}</td>
                                                <td>{{ $file->original_name }}</td>
                                                <td>{{ number_format($file->file_size / 1024, 2) }} KB</td>
                                                <td>
                                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-file-btn" data-file-id="{{ $file->id }}">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- File Upload Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="border-bottom pb-2 mb-0" style="color: #00349C;">
                                        <i class="fa fa-file-upload mr-2"></i>Upload New Files
                                    </h5>
                                    <button type="button" class="btn btn-sm btn-success" id="addFileBtn">
                                        <i class="fa fa-plus mr-1"></i>Add File
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12" id="filesContainer">
                                <!-- Empty initially - files will be added via JavaScript -->
                            </div>
                        </div>
                        
                        <!-- Additional Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3" style="color: #00349C;">
                                    <i class="fa fa-info-circle mr-2"></i>Additional Information
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Entity
                                    </label>
                                    <select id="entity_id" 
                                            name="entity_id" 
                                            class="form-control form-control-lg @error('entity_id') is-invalid @enderror">
                                        <option value="">Select Entity</option>
                                        @foreach($entities as $entity)
                                            <option value="{{ $entity->id }}" {{ old('entity_id', $case->entity_id) == $entity->id ? 'selected' : '' }}>
                                                {{ $entity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('entity_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select id="status" 
                                            name="status" 
                                            class="form-control form-control-lg @error('status') is-invalid @enderror" 
                                            required>
                                        <option value="Open" {{ old('status', $case->status) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Closed" {{ old('status', $case->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg mr-2" style="background-color: #00349C; color: white;">
                                        <i class="fa fa-save mr-2"></i>Update Case
                                    </button>
                                    <a href="{{ route('cases.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fa fa-times mr-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    border-bottom: none;
}

.form-control-lg {
    border-radius: 6px;
}

.btn-lg {
    border-radius: 6px;
    padding: 0.75rem 2rem;
    font-weight: 500;
}

.file-item {
    background-color: #f8f9fa;
}
</style>


<script>
$(document).ready(function() {
    let fileIndex = {{ count(old('files', [['file_name' => '', 'file' => '']])) }};
    
    // Store original judge name value
    var originalJudgeName = '{{ $case->judge_name ?? '' }}';
    
    // Court selection handler
    $('#court_id').on('change', function() {
        var courtId = $(this).val();
        var benchSelect = $('#work_bench_id');
        var benchField = $('#benchField');
        var judgeField = $('#judgeField');
        var judgeInput = $('#judge_name');
        
        // Preserve current judge name value
        var currentJudgeName = judgeInput.val() || originalJudgeName;
        
        // Hide both fields initially
        benchField.hide();
        judgeField.hide();
        benchSelect.html('<option value="">Select Bench</option>');
        benchSelect.val('');
        benchSelect.prop('required', false);
        judgeInput.prop('required', false);
        
        if (courtId) {
            $.ajax({
                url: '{{ route("api.get-benches") }}',
                type: 'GET',
                data: { court_id: courtId },
                dataType: 'json',
                success: function(response) {
                    var courtType = response.court_type;
                    
                    if (courtType === 'High Court' || courtType === 'Supreme Court') {
                        // Show bench field
                        benchField.show();
                        judgeField.hide();
                        judgeInput.val(''); // Clear judge name for High/Supreme Court
                        benchSelect.prop('required', true);
                        judgeInput.prop('required', false);
                        
                        // Load benches
                        benchSelect.html('<option value="">Select Bench</option>');
                        if (response.benches && response.benches.length > 0) {
                            var currentBenchId = {{ $case->work_bench_id ?? 'null' }};
                            $.each(response.benches, function(index, bench) {
                                var selected = (currentBenchId && bench.id == currentBenchId) ? 'selected' : '';
                                benchSelect.append('<option value="' + bench.id + '" ' + selected + '>' + bench.name + '</option>');
                            });
                        }
                    } else if (courtType === 'Session Court') {
                        // Show judge name field
                        benchField.hide();
                        judgeField.show();
                        benchSelect.val('');
                        benchSelect.prop('required', false);
                        judgeInput.prop('required', true);
                        // Restore judge name value
                        if (currentJudgeName && !judgeInput.val()) {
                            judgeInput.val(currentJudgeName);
                        }
                    }
                },
                error: function() {
                    benchField.hide();
                    judgeField.hide();
                }
            });
        }
    });
    
    // File upload handlers
    $('#addFileBtn').click(function() {
        const fileHtml = `
            <div class="file-item mb-3 p-3 border rounded">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                File Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="files[${fileIndex}][file_name]" 
                                   class="form-control form-control-lg" 
                                   placeholder="Enter file name">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                File <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   name="files[${fileIndex}][file]" 
                                   class="form-control form-control-lg" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Accepted: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-file-btn">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#filesContainer').append(fileHtml);
        fileIndex++;
        updateRemoveButtons();
    });
    
    $(document).on('click', '.remove-file-btn', function() {
        $(this).closest('.file-item').remove();
        updateRemoveButtons();
    });
    
    function updateRemoveButtons() {
        const fileItems = $('.file-item');
        if (fileItems.length > 1) {
            fileItems.each(function() {
                $(this).find('.remove-file-btn').show();
            });
            fileItems.first().find('.remove-file-btn').hide();
        } else {
            fileItems.find('.remove-file-btn').hide();
        }
    }
    
    // Initialize file upload section - add first file input if container is empty
    if ($('#filesContainer .file-item').length === 0) {
        $('#addFileBtn').trigger('click');
    } else {
        updateRemoveButtons();
    }
    
    // Trigger court change on page load if court is already selected
    var initialCourtId = $('#court_id').val();
    if (initialCourtId) {
        // Small delay to ensure DOM is ready
        setTimeout(function() {
            $('#court_id').trigger('change');
        }, 100);
    }
    
    // Delete file handler
    $('.delete-file-btn').click(function() {
        if (confirm('Are you sure you want to delete this file?')) {
            var fileId = $(this).data('file-id');
            $.ajax({
                url: '/cases/files/' + fileId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('Error deleting file');
                }
            });
        }
    });
});
</script>
@endsection
