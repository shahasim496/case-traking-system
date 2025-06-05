@extends('layouts.main')
@section('title', 'Evidence Receipts')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-start">
                <h4 class="mb-0">Evidence Receipts</h4>
                <div class="d-flex flex-column align-items-end">

                @if(auth()->user()->can('manage reports'))
                    <div class="mb-3">
                        <button class="btn me-2" style="background-color: #007bff; color: white;" onclick="exportToExcel()">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                        <button class="btn" style="background-color: #007bff; color: white;" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf"></i> Export to PDF
                        </button>
                    </div>
                    @endif
                    <form method="GET" action="{{ route('evidence.receipts') }}" class="d-flex align-items-center">
                        <!-- Filter by Type -->
                        <label for="type" class="me-2 mb-0"><strong>Type:</strong></label>
                        <select name="type" id="type" class="form-select form-select-lg me-4" style="width: 200px;" onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="currency" {{ request('type') == 'currency' ? 'selected' : '' }}>Currency</option>
                            <option value="dna" {{ request('type') == 'dna' ? 'selected' : '' }}>DNA</option>
                            <option value="ballistics" {{ request('type') == 'ballistics' ? 'selected' : '' }}>Ballistics</option>
                            <option value="toxicology" {{ request('type') == 'toxicology' ? 'selected' : '' }}>Toxicology</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="questioned_document" {{ request('type') == 'questioned_document' ? 'selected' : '' }}>Questioned Document</option>
                        </select>

                        <!-- Pagination Dropdown -->
                        <label style="margin-left: 10px;" for="perPage" class="me-2 mb-0"><strong>Show:</strong></label>
                        <select name="perPage" id="perPage" class="form-select form-select-lg" style="width: 150px;" onchange="this.form.submit()">
                            <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Reference Number</th>
                            <th>Officer ID</th>
                            <th>Officer Name</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evidences as $evidence)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($evidence->type) }}</td>
                                <td>GFSL-{{ date('Y', strtotime($evidence->created_at)) }}-{{ $evidence->id }}</td>
                                <td>{{ $evidence->officer_id }}</td>
                                <td>{{ $evidence->officer_name }}</td>
                                <td>{{ $evidence->designation }}</td>
                                <td>
                                    <span class="badge 
                                        @if($evidence->status == 'pending') bg-danger 
                                        @elseif($evidence->status == 'verified') bg-warning 
                                        @elseif($evidence->status == 'completed') bg-success 
                                        @endif">
                                        {{ ucfirst($evidence->status) }}
                                    </span>
                                </td>
                                <td>{{ $evidence->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('evidence.receipt', $evidence->id) }}" class="btn" style="background-color: #007bff; color: white;">View Receipt</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No evidence receipts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <p class="mb-0">Showing {{ $evidences->firstItem() }} to {{ $evidences->lastItem() }} of {{ $evidences->total() }} records</p>
                {{ $evidences->appends(['perPage' => request('perPage'), 'type' => request('type')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@section('jsfile')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    function exportToExcel() {
        // Get the table data
        const table = document.querySelector('table');
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        
        // Prepare data for Excel
        const data = rows.map(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            return cells.map(cell => {
                // Skip the last cell (Actions column)
                if (cell === cells[cells.length - 1]) return null;
                return cell.textContent.trim();
            }).filter(cell => cell !== null);
        });

        // Add headers
        const headers = ['#', 'Type', 'Reference Number', 'Officer ID', 'Officer Name', 'Designation', 'Status', 'Created At'];
        data.unshift(headers);

        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Evidence Receipts");

        // Save file
        XLSX.writeFile(wb, "evidence_receipts.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Get the table data
        const table = document.querySelector('table');
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        
        // Prepare data for PDF
        const data = rows.map(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            return cells.map(cell => {
                // Skip the last cell (Actions column)
                if (cell === cells[cells.length - 1]) return null;
                return cell.textContent.trim();
            }).filter(cell => cell !== null);
        });

        // Add headers
        const headers = ['#', 'Type', 'Reference Number', 'Officer ID', 'Officer Name', 'Designation', 'Status', 'Created At'];
        
        // Add table to PDF
        doc.autoTable({
            head: [headers],
            body: data,
            startY: 20,
            theme: 'grid',
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [0, 102, 204]
            }
        });
        
        // Add title
        doc.setFontSize(16);
        doc.text('Evidence Receipts Report', 14, 15);
        
        // Save file
        doc.save("evidence_receipts.pdf");
    }
</script>
@endsection
@endsection 