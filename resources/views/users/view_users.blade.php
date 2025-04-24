@extends('layouts.main')
@section('title','All Users')
@section('breadcrumb','All Users')

@section('content')
<div class="listing">
    <div class="table-responsive card">


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
</script>

@endsection
