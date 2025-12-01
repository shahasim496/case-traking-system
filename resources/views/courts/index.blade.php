@extends('layouts.main')
@section('title', 'Manage Courts')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-gavel mr-2"></i>Manage Courts
                    </h4>
                    <a href="{{ route('courts.create') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add Court
                    </a>
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
                                    <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search courts by name...">
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
                        <table class="table table-striped table-bordered" id="courtsTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="40%">Court Name</th>
                                    <th width="40%">Description</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courts as $index => $court)
                                <tr>
                                    <td>{{ ($courts->currentPage() - 1) * $courts->perPage() + $index + 1 }}</td>
                                    <td>{{ $court->name }}</td>
                                    <td>{{ $court->description ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('courts.edit', $court->id) }}" class="btn d-flex align-items-center justify-content-center" style="width: 80px; background-color: #00349C; color: white;" title="Edit">
                                                <i class="fa fa-edit mr-1"></i>Edit
                                            </a>
                                            <button class="btn btn-danger d-flex align-items-center justify-content-center delete-btn" 
                                                    style="width: 80px; background-color: #dc3545; color: white;"
                                                    data-id="{{ $court->id }}" 
                                                    data-name="{{ $court->name }}"
                                                    data-toggle="modal" 
                                                    data-target="#deleteModal" 
                                                    title="Delete">
                                                <i class="fa fa-trash mr-1"></i>Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No courts found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $courts->links() }}
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
                <p>Are you sure you want to delete court: <strong id="deleteCourtName"></strong>?</p>
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

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-danger {
    color: #fff !important;
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 6px !important;
}

.btn-danger:hover {
    color: #fff !important;
    background-color: #c82333 !important;
    border-color: #bd2130 !important;
    transform: translateY(-1px);
}

.btn[style*="background-color: #00349C"] {
    border-color: #00349C;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 6px !important;
}

.btn[style*="background-color: #00349C"]:hover {
    background-color: #002a7a !important;
    border-color: #002a7a;
    transform: translateY(-1px);
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
    $('.delete-btn').on('click', function () {
        var id = $(this).data('id');
        var name = $(this).data('name') || 'this court';
        var url = "{{ route('courts.delete', ':id') }}".replace(':id', id);
        
        $('#deleteCourtName').text(name);
        $('#deleteForm').attr('action', url);
    });

    // Search functionality
    $('#clearSearchBtn').click(function() {
        $('#searchInput').val('');
        filterTable('');
    });
    
    $('#searchInput').on('keyup', function(e) {
        filterTable($(this).val().toLowerCase());
    });
    
    function filterTable(searchTerm) {
        $('#courtsTable tbody tr').each(function() {
            var courtName = $(this).find('td:eq(1)').text().toLowerCase();
            var description = $(this).find('td:eq(2)').text().toLowerCase();
            if (courtName.indexOf(searchTerm) > -1 || description.indexOf(searchTerm) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    // Export to Excel functionality
    $('#exportExcelBtn').click(function() {
        exportToExcel();
    });
    
    function exportToExcel() {
        var table = document.getElementById('courtsTable');
        var html = table.outerHTML;
        
        // Create a temporary link element
        var link = document.createElement('a');
        link.download = 'courts_export.xls';
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
        // Get all data from table
        var data = [];
        $('#courtsTable tbody tr:visible').each(function(index) {
            var courtName = $(this).find('td:eq(1)').text();
            var description = $(this).find('td:eq(2)').text();
            data.push([
                index + 1,
                courtName,
                description || 'N/A'
            ]);
        });
        
        // Create PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add title
        doc.setFontSize(20);
        doc.setTextColor(0, 52, 156); // #00349C
        doc.text('Courts Report', 105, 20, { align: 'center' });
        
        // Add date
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Generated on: ' + new Date().toLocaleDateString() + ' at ' + new Date().toLocaleTimeString(), 105, 30, { align: 'center' });
        
        // Define table headers
        var headers = ['#', 'Court Name', 'Description'];
        
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
                0: { cellWidth: 20 }, // #
                1: { cellWidth: 60 }, // Court Name
                2: { cellWidth: 110 }, // Description
            },
            didDrawPage: function(data) {
                // Add footer
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Total Courts: ' + data.length, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }
        });
        
        // Save the PDF
        doc.save('courts_report.pdf');
    }
});
</script>
@endsection

