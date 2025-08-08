@extends('layouts.main')
@section('title', 'Manage Permissions')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-shield mr-2"></i>Manage Permissions
                    </h4>
                    @can('create permission')
                    <a href="{{ route('permissions.create') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add Permission
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
                                    <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search permissions by name...">
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
                        <table class="table table-striped table-bordered" id="permissionsTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="70%">Permission Name</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTable will populate this -->
                            </tbody>
                        </table>
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
                <p>Are you sure you want to delete permission: <strong id="deletePermissionName"></strong>?</p>
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

/* Action button styles to match user index */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-warning:hover {
    color: #212529;
    background-color: #e0a800;
    border-color: #d39e00;
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

/* Custom button styles to match user index exactly */
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
    // Initialize DataTable
    var table = $('#permissionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('permissions.getPermissions') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        pageLength: 50,
        paging: false,
        info: false,
        searching: false,
        responsive: true,
        language: {
            emptyTable: "No permissions found",
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
        }
    });

    // Handle delete button click
    $('#permissionsTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name') || 'this permission';
        var url = "{{ route('permissions.delete', ':id') }}".replace(':id', id);
        
        $('#deletePermissionName').text(name);
        $('#deleteForm').attr('action', url);
    });

    // Search functionality
    $('#clearSearchBtn').click(function() {
        $('#searchInput').val('');
        table.search('').draw();
    });
    
    $('#searchInput').on('keyup', function(e) {
        table.search(this.value).draw();
    });
    
    // Export to Excel functionality
    $('#exportExcelBtn').click(function() {
        exportToExcel();
    });
    
    function exportToExcel() {
        var table = document.getElementById('permissionsTable');
        var html = table.outerHTML;
        
        // Create a temporary link element
        var link = document.createElement('a');
        link.download = 'permissions_export.xls';
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
        // Get all data from DataTable
        var data = [];
        table.rows().every(function() {
            var rowData = this.data();
            data.push([
                rowData.DT_RowIndex,
                rowData.name
            ]);
        });
        
        // Create PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add title
        doc.setFontSize(20);
        doc.setTextColor(0, 52, 156); // #00349C
        doc.text('Permissions Report', 105, 20, { align: 'center' });
        
        // Add date
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Generated on: ' + new Date().toLocaleDateString() + ' at ' + new Date().toLocaleTimeString(), 105, 30, { align: 'center' });
        
        // Define table headers
        var headers = ['#', 'Permission Name'];
        
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
                1: { cellWidth: 70 }, // Permission Name
            },
            didDrawPage: function(data) {
                // Add footer
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Total Permissions: ' + data.length, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }
        });
        
        // Save the PDF
        doc.save('permissions_report.pdf');
    }
});
</script>
@endsection