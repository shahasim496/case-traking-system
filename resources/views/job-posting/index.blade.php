@extends('layouts.main')
@section('title', 'Manage Job Postings')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-briefcase mr-2"></i>Manage Job Postings
                    </h4>
                    @can('create job posting')
                    <a href="{{ route('job.posting') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add Job Posting
                    </a>
                    @endcan
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <!-- Search and Export Section -->
                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-6 mb-3 mb-md-0">
                            <div class="search-container">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="fa fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search job postings by title, department, or description...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" id="clearSearchBtn" title="Clear Search">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="export-container d-flex justify-content-end">
                                <button type="button" class="btn btn-success mr-2" id="exportExcelBtn">
                                    <i class="fa fa-file-excel-o mr-1"></i>Excel
                                </button>
                                <button type="button" class="btn btn-danger" id="exportPdfBtn">
                                    <i class="fa fa-file-pdf-o mr-1"></i>PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="jobPostingsTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Job Title</th>
                                    <th width="15%">Department</th>
                                    <th width="10%">Positions</th>
                                    <th width="15%">Deadline</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Created</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobPostings as $index => $jobPosting)
                                    <tr>
                                        <td>{{ $index + 1 + ($jobPostings->currentPage() - 1) * $jobPostings->perPage() }}</td>
                                        <td>
                                            <strong>{{ strip_tags($jobPosting->title) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit(strip_tags($jobPosting->description), 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $jobPosting->department->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $jobPosting->positions }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $deadline = \Carbon\Carbon::parse($jobPosting->deadline);
                                                $now = \Carbon\Carbon::now();
                                                $isExpired = $deadline->isPast();
                                                $isNearExpiry = $deadline->diffInDays($now) <= 7;
                                            @endphp
                                            
                                            @if($isExpired)
                                                <span class="text-danger">
                                                    <i class="fa fa-clock mr-1"></i>{{ $deadline->format('M d, Y') }}
                                                    <br><small>Expired</small>
                                                </span>
                                            @elseif($isNearExpiry)
                                                <span class="text-warning">
                                                    <i class="fa fa-clock mr-1"></i>{{ $deadline->format('M d, Y') }}
                                                    <br><small>Expires Soon</small>
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="fa fa-clock mr-1"></i>{{ $deadline->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($jobPosting->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($jobPosting->status == 'draft')
                                                <span class="badge badge-warning">Draft</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $jobPosting->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @can('view job posting')
                                                <a href="{{ route('job-posting.show', $jobPosting->id) }}" 
                                                   class="btn d-flex align-items-center justify-content-center" 
                                                   style="width: 80px; background-color: #17a2b8; color: white;" 
                                                   title="View">
                                                    <i class="fa fa-eye mr-1"></i>View
                                                </a>
                                                @endcan
                                                
                                                @can('edit job posting')
                                                <a href="{{ route('job-posting.edit', $jobPosting->id) }}" 
                                                   class="btn d-flex align-items-center justify-content-center" 
                                                   style="width: 80px; background-color: #00349C; color: white;" 
                                                   title="Edit">
                                                    <i class="fa fa-edit mr-1"></i>Edit
                                                </a>
                                                @endcan
                                                
                                                @can('delete job posting')
                                                <button class="btn btn-danger d-flex align-items-center justify-content-center delete-btn" 
                                                        style="width: 80px;"
                                                        data-id="{{ $jobPosting->id }}" 
                                                        data-title="{{ $jobPosting->title }}"
                                                        data-toggle="modal" 
                                                        data-target="#deleteModal" 
                                                        title="Delete">
                                                        <i class="fa fa-trash mr-1"></i>Delete
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No job postings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $jobPostings->appends(['status' => request('status'), 'per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete job posting: <strong id="deleteJobTitle"></strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash mr-1"></i>Delete
                    </button>
                </form>
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

.table {
    border-radius: 8px;
    overflow: hidden;
}

.thead-dark th {
    background-color: #343a40;
    border-color: #454d55;
    color: white;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.d-flex.gap-1 .btn {
    margin-right: 0.25rem;
}

.d-flex.gap-1 .btn:last-child {
    margin-right: 0;
}

.badge {
    font-size: 0.75em;
    border-radius: 4px;
}

.modal-content {
    border-radius: 10px;
    border: none;
}

.modal-header {
    border-radius: 10px 10px 0 0;
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #00349C;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #00349C;
    border-color: #00349C;
}

/* Improved Search and Export Styles */
.search-container {
    position: relative;
}

.search-container .input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.search-container .input-group-text {
    border: 1px solid #ced4da;
    border-right: none;
}

.search-container .form-control {
    border: 1px solid #ced4da;
    border-left: none;
    padding: 0.75rem 1rem;
}

.search-container .form-control:focus {
    box-shadow: none;
    border-color: #00349C;
}

.search-container .btn {
    border: 1px solid #ced4da;
    border-left: none;
    padding: 0.75rem 1rem;
}

.export-container .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.export-container .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.export-container .btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.export-container .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

@media (max-width: 768px) {
    .d-flex.gap-1 .btn {
        margin-bottom: 0.25rem;
        margin-right: 0.25rem;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .export-container {
        justify-content: center !important;
        margin-top: 15px;
    }
    
    .search-container {
        margin-bottom: 15px;
    }
}
</style>

<!-- Include jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function() {
    // Handle delete button click
    $('.delete-btn').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var url = "{{ route('job-posting.destroy', ':id') }}".replace(':id', id);
        
        $('#deleteJobTitle').text(title);
        $('#deleteForm').attr('action', url);
    });
    
    // Search functionality
    $('#clearSearchBtn').click(function() {
        $('#searchInput').val('');
        performSearch();
    });
    
    $('#searchInput').on('keyup', function(e) {
        performSearch();
    });
    
    function performSearch() {
        var searchTerm = $('#searchInput').val().toLowerCase();
        var table = $('#jobPostingsTable tbody');
        var rows = table.find('tr');
        var visibleCount = 0;
        
        rows.each(function() {
            var row = $(this);
            var text = row.text().toLowerCase();
            
            if (text.includes(searchTerm) || searchTerm === '') {
                row.show();
                visibleCount++;
            } else {
                row.hide();
            }
        });
        
        // Update row numbers for visible rows
        updateRowNumbers();
        
        // Show "no results" message if needed
        if (visibleCount === 0 && searchTerm !== '') {
            if (table.find('.no-results').length === 0) {
                table.append('<tr class="no-results"><td colspan="8" class="text-center text-muted">No job postings found matching your search.</td></tr>');
            }
        } else {
            table.find('.no-results').remove();
        }
    }
    
    function updateRowNumbers() {
        var visibleRows = $('#jobPostingsTable tbody tr:visible:not(.no-results)');
        visibleRows.each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    
    // Export to Excel functionality
    $('#exportExcelBtn').click(function() {
        exportToExcel();
    });
    
    function exportToExcel() {
        var table = document.getElementById('jobPostingsTable');
        var html = table.outerHTML;
        
        // Create a temporary link element
        var link = document.createElement('a');
        link.download = 'job_postings_export.xls';
        link.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // Export to PDF functionality
    $('#exportPdfBtn').click(function() {
        exportToPDF();
    });
    
    function exportToPDF() {
        // Get visible rows only (respect search filter)
        var visibleRows = $('#jobPostingsTable tbody tr:visible:not(.no-results)');
        
        // Prepare data for PDF
        var data = [];
        visibleRows.each(function() {
            var row = $(this);
            var rowData = [];
            
            // Get text content from each cell (excluding actions column)
            row.find('td').each(function(index) {
                if (index < 7) { // Exclude actions column
                    var cellText = $(this).text().trim();
                    rowData.push(cellText);
                }
            });
            
            if (rowData.length > 0) {
                data.push(rowData);
            }
        });
        
        // Create PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add title
        doc.setFontSize(20);
        doc.setTextColor(0, 52, 156); // #00349C
        doc.text('Job Postings Report', 105, 20, { align: 'center' });
        
        // Add date
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Generated on: ' + new Date().toLocaleDateString() + ' at ' + new Date().toLocaleTimeString(), 105, 30, { align: 'center' });
        
        // Define table headers
        var headers = ['#', 'Job Title', 'Department', 'Positions', 'Deadline', 'Status', 'Created'];
        
        // Add table
        doc.autoTable({
            head: [headers],
            body: data,
            startY: 40,
            styles: {
                fontSize: 10,
                cellPadding: 3,
                lineColor: [0, 0, 0],
                lineWidth: 0.1,
            },
            headStyles: {
                fillColor: [52, 58, 64], // Dark gray
                textColor: [255, 255, 255],
                fontStyle: 'bold',
            },
            alternateRowStyles: {
                fillColor: [248, 249, 250], // Light gray
            },
            columnStyles: {
                0: { cellWidth: 10 }, // #
                1: { cellWidth: 40 }, // Job Title
                2: { cellWidth: 25 }, // Department
                3: { cellWidth: 20 }, // Positions
                4: { cellWidth: 25 }, // Deadline
                5: { cellWidth: 20 }, // Status
                6: { cellWidth: 20 }, // Created
            },
            didDrawPage: function(data) {
                // Add footer
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Total Job Postings: ' + data.length, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }
        });
        
        // Save the PDF
        doc.save('job_postings_report.pdf');
    }
});
</script>
@endsection 