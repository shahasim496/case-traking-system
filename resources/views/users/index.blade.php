@extends('layouts.main')
@section('title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #00349C;">
                    <h4 class="mb-0">
                        <i class="fa fa-users mr-2"></i>Manage Users
                    </h4>
                    @can('create user')
                    <a href="{{ route('user.create') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus mr-1"></i>Add User
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
                                    <input type="text" id="searchInput" class="form-control border-left-0" placeholder="Search users by name, email, CNIC, or phone...">
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
                        <table class="table table-striped table-bordered" id="usersTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Name</th>
                                    <th width="12%">CNIC</th>
                                    <th width="18%">Email</th>
                                    <th width="12%">Phone</th>
                                        <th width="12%">Entity</th>
                                    <th width="12%">Designation</th>
                                    <th width="8%">Status</th>
                                    <th width="6%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->cnic ?? '-' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                        <td>{{ $user->entity->name ?? '-' }}</td>
                                        <td>{{ $user->designation->name ?? '-' }}</td>
                                        <td>
                                            @if($user->is_blocked == 0)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Banned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                             
                                                
                                                                                                 @can('edit user')
                                                 <a href="{{ route('user.edit', $user->id) }}" class="btn d-flex align-items-center justify-content-center" style="width: 80px; background-color: #00349C; color: white;" title="Edit">
                                                 <i class="fa fa-edit mr-1"></i>Edit
                                                 </a>
                                                 @endcan
                                                 
                                                 @can('ban user')
                                                 <button class="btn d-flex align-items-center justify-content-center {{ $user->is_blocked == 0 ? 'btn-danger' : 'btn-success' }} ban-btn" 
                                                         style="width: 80px;"
                                                         title="{{ $user->is_blocked == 0 ? 'Ban User' : 'Unban User' }}"
                                                         data-id="{{ $user->id }}"
                                                         data-status="{{ $user->is_blocked }}"
                                                         data-name="{{ $user->name }}">
                                                     <i class="fa {{ $user->is_blocked == 0 ? 'fa-ban' : 'fa-check' }} mr-1"></i>{{ $user->is_blocked == 0 ? 'Ban' : 'Unban' }}
                                                 </button>
                                                 @endcan
                                                 
                                                 @can('delete user')
                                                 <button class="btn btn-danger d-flex align-items-center justify-content-center delete-btn" 
                                                         style="width: 80px;"
                                                         data-id="{{ $user->id }}" 
                                                         data-name="{{ $user->name }}"
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
                                        <td colspan="9" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(['status' => request('status'), 'per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
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
                <p>Are you sure you want to delete user: <strong id="deleteUserName"></strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>Cancel
                </button>
                <form id="deleteForm" method="GET" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash mr-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ban/Unban Confirmation Modal -->
<div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="banModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="banModalLabel">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Confirm Action
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="banModalText"></p>
                <p class="text-muted small">This action can be reversed later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i>Cancel
                </button>
                <form id="banForm" method="GET" action="">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-check mr-1"></i>Confirm
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
        var name = $(this).data('name');
        var url = "{{ route('user.delete', ':id') }}".replace(':id', id);
        
        $('#deleteUserName').text(name);
        $('#deleteForm').attr('action', url);
    });
    
    // Handle ban/unban button click
    $('.ban-btn').click(function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var name = $(this).data('name');
        var url = "{{ route('user.banned', ':id') }}".replace(':id', id);
        
        var action = status == 0 ? 'ban' : 'unban';
        var modalText = 'Are you sure you want to ' + action + ' user: <strong>' + name + '</strong>?';
        
        $('#banModalText').html(modalText);
        $('#banForm').attr('action', url);
        $('#banModal').modal('show');
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
        var table = $('#usersTable tbody');
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
                table.append('<tr class="no-results"><td colspan="9" class="text-center text-muted">No users found matching your search.</td></tr>');
            }
        } else {
            table.find('.no-results').remove();
        }
    }
    
    function updateRowNumbers() {
        var visibleRows = $('#usersTable tbody tr:visible:not(.no-results)');
        visibleRows.each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    
    // Export to Excel functionality
    $('#exportExcelBtn').click(function() {
        exportToExcel();
    });
    
    function exportToExcel() {
        var table = document.getElementById('usersTable');
        var html = table.outerHTML;
        
        // Create a temporary link element
        var link = document.createElement('a');
        link.download = 'users_export.xls';
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
        var visibleRows = $('#usersTable tbody tr:visible:not(.no-results)');
        
        // Prepare data for PDF
        var data = [];
        visibleRows.each(function() {
            var row = $(this);
            var rowData = [];
            
            // Get text content from each cell (excluding actions column)
            row.find('td').each(function(index) {
                if (index < 8) { // Exclude actions column
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
        doc.text('Users Report', 105, 20, { align: 'center' });
        
        // Add date
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Generated on: ' + new Date().toLocaleDateString() + ' at ' + new Date().toLocaleTimeString(), 105, 30, { align: 'center' });
        
        // Define table headers
        var headers = ['#', 'Name', 'CNIC', 'Email', 'Phone', 'Entity', 'Designation', 'Status'];
        
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
                1: { cellWidth: 30 }, // Name
                2: { cellWidth: 25 }, // CNIC
                3: { cellWidth: 40 }, // Email
                4: { cellWidth: 25 }, // Phone
                5: { cellWidth: 25 }, // Entity
                6: { cellWidth: 25 }, // Designation
                7: { cellWidth: 20 }, // Status
            },
            didDrawPage: function(data) {
                // Add footer
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100);
                doc.text('Total Users: ' + data.length, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }
        });
        
        // Save the PDF
        doc.save('users_report.pdf');
    }
});
</script>
@endsection 