@extends('layouts.main')
@section('title', 'Case Tracking')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-gavel mr-2"></i>Case Tracking
                    </h4>
                    @if(auth()->user()->can('add case'))
                    <a href="{{ route('cases.create') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add Case
                    </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    @include('components.toaster')
                    
                    <!-- Search, Filters and Export Section -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3 mb-md-0">
                            <div class="search-container">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="fa fa-search text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search cases..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary border-left-0" type="button" id="clearSearchBtn" title="Clear Search">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3 mb-md-0">
                            <select class="form-control filter-select" id="courtTypeFilter" name="court_type">
                                <option value="">All Court Types</option>
                                <option value="High Court" {{ request('court_type') == 'High Court' ? 'selected' : '' }}>High Court</option>
                                <option value="Supreme Court" {{ request('court_type') == 'Supreme Court' ? 'selected' : '' }}>Supreme Court</option>
                                <option value="Session Court" {{ request('court_type') == 'Session Court' ? 'selected' : '' }}>Session Court</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3 mb-md-0">
                            <select class="form-control filter-select" id="statusFilter" name="status">
                                <option value="">All Status</option>
                                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3 mb-md-0">
                            <select class="form-control filter-select" id="departmentFilter" name="department_id">
                                <option value="">All Departments</option>
                                @foreach(\App\Models\Department::all() as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
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
                        <table class="table table-striped table-bordered" id="casesTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Case Number</th>
                                    <th width="15%">Court Type</th>
                                    <th width="15%">Department</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Notices</th>
                                    <th width="30%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cases as $index => $case)
                                    <tr>
                                        <td>{{ $index + 1 + ($cases->currentPage() - 1) * $cases->perPage() }}</td>
                                        <td><strong>{{ $case->case_number }}</strong></td>
                                        <td>{{ $case->court_type }}</td>
                                        <td>{{ $case->department->name ?? '-' }}</td>
                                        <td>
                                            @if($case->status == 'Open')
                                                <span class="badge badge-success">Open</span>
                                            @else
                                                <span class="badge badge-danger">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $case->notices->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if(auth()->user()->can('view case'))
                                                <a href="{{ route('cases.show', $case->id) }}" 
                                                   class="btn btn-sm d-flex align-items-center justify-content-center" 
                                                   style="width: 80px; background-color: #17a2b8; color: white;"
                                                   title="View">
                                                    <i class="fa fa-eye mr-1"></i>View
                                                </a>
                                                @endif
                                                @if(auth()->user()->can('edit case'))
                                                <a href="{{ route('cases.edit', $case->id) }}" 
                                                   class="btn btn-sm d-flex align-items-center justify-content-center" 
                                                   style="width: 80px; background-color: #00349C; color: white;" 
                                                   title="Edit">
                                                    <i class="fa fa-edit mr-1"></i>Edit
                                                </a>
                                                @endif
                                                @if(auth()->user()->can('delete case'))
                                                <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center" 
                                                        style="width: 80px;"
                                                        data-id="{{ $case->id }}" 
                                                        data-name="{{ $case->case_number }}"
                                                        data-toggle="modal" 
                                                        data-target="#deleteModal" 
                                                        title="Delete">
                                                    <i class="fa fa-trash mr-1"></i>Delete
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No cases found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $cases->appends(request()->query())->links('pagination::bootstrap-4') }}
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
                <p>Are you sure you want to delete case: <strong id="deleteCaseName"></strong>?</p>
                <p class="text-muted small">This action cannot be undone. All related notices and hearings will also be deleted.</p>
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
        var name = $(this).data('name');
        var url = "{{ route('cases.destroy', ':id') }}".replace(':id', id);
        
        $('#deleteCaseName').text(name);
        $('#deleteForm').attr('action', url);
    });
    
    // Server-side filtering function
    function applyFilters() {
        var search = $('#searchInput').val();
        var courtType = $('#courtTypeFilter').val();
        var status = $('#statusFilter').val();
        var department = $('#departmentFilter').val();
        
        var url = "{{ route('cases.index') }}?";
        var params = [];
        
        if (search) params.push('search=' + encodeURIComponent(search));
        if (courtType) params.push('court_type=' + encodeURIComponent(courtType));
        if (status) params.push('status=' + encodeURIComponent(status));
        if (department) params.push('department_id=' + encodeURIComponent(department));
        
        if (params.length > 0) {
            url += params.join('&');
        }
        
        window.location.href = url;
    }
    
    // Clear search button
    $('#clearSearchBtn').click(function() {
        $('#searchInput').val('');
        applyFilters();
    });
    
    // Search input with debounce (server-side)
    var searchTimeout;
    $('#searchInput').on('keyup', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFilters();
        }, 500); // Wait 500ms after user stops typing
    });
    
    // Enter key on search input
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            clearTimeout(searchTimeout);
            applyFilters();
        }
    });
    
    // Filter selects change event (server-side)
    $('.filter-select').on('change', function() {
        applyFilters();
    });
    
    // Export to Excel functionality
    $('#exportExcelBtn').click(function() {
        exportToExcel();
    });
    
    function exportToExcel() {
        var table = document.getElementById('casesTable');
        var html = table.outerHTML;
        
        // Create a temporary link element
        var link = document.createElement('a');
        link.download = 'cases_export.xls';
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
        // Get all rows from current page (server-side filtered data)
        var rows = $('#casesTable tbody tr:not(.no-results)');
        
        // Prepare data for PDF
        var data = [];
        rows.each(function() {
            var row = $(this);
            var rowData = [];
            
            // Get text content from each cell (excluding actions column)
            row.find('td').each(function(index) {
                if (index < 6) { // Exclude actions column (6 data columns: #, Case Number, Court Type, Department, Status, Notices)
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
        doc.text('Cases Report', 105, 20, { align: 'center' });
        
        // Add date
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Generated on: ' + new Date().toLocaleDateString() + ' at ' + new Date().toLocaleTimeString(), 105, 30, { align: 'center' });
        
        // Define table headers
        var headers = ['#', 'Case Number', 'Court Type', 'Department', 'Status', 'Notices'];
        
        // Add table
        doc.autoTable({
            head: [headers],
            body: data,
            startY: 40,
            styles: {
                fontSize: 9,
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
                1: { cellWidth: 30 }, // Case Number
                2: { cellWidth: 30 }, // Court Type
                3: { cellWidth: 30 }, // Department
                4: { cellWidth: 20 }, // Status
                5: { cellWidth: 20 }, // Notices
            },
            didDrawPage: function(data) {
                // Add footer
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Page ' + doc.internal.getCurrentPageInfo().pageNumber, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }
        });
        
        // Save the PDF
        doc.save('cases_report.pdf');
    }
});
</script>
@endsection

