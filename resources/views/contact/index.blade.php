@extends('layouts.main')
@section('title','Contact List')
@section('breadcrumb','Contact List')

@can('Write Contact Information')
    @section('add','Add')
@endcan

@section('link',route('contact.create',$officerObj->id))
@section('back',route('officer.view',$officerObj->id))

@section('content')
<div class="listing">
  <div class="table-responsive card">
<table id="table_id" class="display all-user dataTable no-footer">
<thead>
<tr>
    <th>Sr.</th>
    <th>Email</th>
    <th>Mobile #</th>
    <th>Landline #</th>
    <th>Present Address</th>
    <th>Temporary Address</th>
    <th style="width:15%">Action</th>
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
        ajax: "{{ route('contact.getContacts',$officerObj->id) }}",
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'email', name: 'email'},
            {data: 'mobile_number', name: 'type'},
            {data: 'landline_number', name: 'landline_number'},
            {data: 'present_address', name: 'present_address'},
            {data: 'temporary_address', name: 'temporary_address'},
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
