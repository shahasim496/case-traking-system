@extends('layouts.main')
@section('title','All Users')
@section('breadcrumb','All Users')

@section('content')
<div class="listing">
    <div class="table-responsive card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Users</h4>
                </div>
                @if(auth()->user()->can('manage reports'))
                <div class="col-md-6 text-right">
                    <button class="btn" style="background-color: #007bff; color: white;" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                    <button class="btn" style="background-color: #007bff; color: white;" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> Export to PDF
                    </button>
                </div>
                @endif
            </div>
        </div>
        <table id="table_id" class="dataTable no-footer table table-secondary">

<thead>
<tr>
    <th>Sr.</th>
    <th>User name</th>
    <th>National ID</th>
 
    <th>Email</th>
    <th>Mobile No</th>
   
 
    <th>Action</th>
</tr>
</thead>
<tbody>


</tbody>
</table>
</div>
</div>
@endsection

@section('jsfile')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    $(function () {

    table.destroy();
    table = $('#table_id').DataTable({
        stateSave: true,
        processing: true,
        serverSide: true,
        ordering:  false,
        ajax: "{{ route('user.getUsers') }}",
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'cnic', name: 'cnic'},
       
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
           
     
            {data: 'action', name: 'action'},
        ]
    });

  });

    function deleteR(id) {
    event.preventDefault();

console.log(id);
    var url = $("#delete_"+id).attr('href');
   console.log(url);
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      confirmButtonClass: 'btn btn-primary',
      cancelButtonClass: 'btn btn-danger ml-1',
      buttonsStyling: false,
    }).then(function (result) {
      console.log(result);
      if (result.value) {
        console.log("yes delete it.");
        window.location.href = url;
      }
      else if (result.dismiss === Swal.DismissReason.cancel) {
        console.log("cancel.");

      }
    });
      // if(!confirm("Are You Sure to delete this"))
      //     event.preventDefault();
  }

    function exportToExcel() {
        // Get current table data including filters
        let data = table.rows({ search: 'applied' }).data();
        let rows = [];
        
        // Add headers
        rows.push(['Sr.', 'User Name', 'National ID', 'Email', 'Mobile No']);
        
        // Add data rows
        data.each(function(row) {
            rows.push([
                row.DT_RowIndex,
                row.name,
                row.cnic,
                row.email,
                row.phone
            ]);
        });
        
        // Create worksheet
        let ws = XLSX.utils.aoa_to_sheet(rows);
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Users");
        
        // Save file
        XLSX.writeFile(wb, "users_report.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Get current table data including filters
        let data = table.rows({ search: 'applied' }).data();
        let rows = [];
        
        // Add data rows
        data.each(function(row) {
            rows.push([
                row.DT_RowIndex,
                row.name,
                row.cnic,
                row.email,
                row.phone
            ]);
        });
        
        // Add table to PDF
        doc.autoTable({
            head: [['Sr.', 'User Name', 'National ID', 'Email', 'Mobile No']],
            body: rows,
            startY: 20,
            theme: 'grid'
        });
        
        // Save file
        doc.save("users_report.pdf");
    }
</script>

@endsection
