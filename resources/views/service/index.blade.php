@extends('layouts.main')
@section('title','Posting Lists')
@section('breadcrumb','Posting Lists')

@can('Write Service Posting History')
    @section('add','Add')
@endcan


@section('link',route('service.create',$officerObj->id))
@section('back',route('officer.view',$officerObj->id))

@section('content')
<div class="listing">
  <div class="table-responsive card">


<table id="table_id" class="display all-user dataTable no-footer">
<thead>
<tr>
    <th>Sr.</th>
    <th>Posting List</th>
    <th>Posting Organization</th>
    <th>Notification Date</th>
    <th>Date From</th>
    <th>Date To</th>
    <th>Post</th>
    <th>Grade</th>
    <th>Division</th>
    <th>Ministry</th>
    <th>Department</th>
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
        ajax: "{{ route('service.getServices',$officerObj->id) }}",
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'post_list', name: 'post_list'},
            {data: 'orgnization_list', name: 'orgnization_list'},
            {data: 'notification_date', name: 'notification_date'},
            {data: 'from', name: 'from'},
            {data: 'to', name: 'to'},
            {data: 'post', name: 'post'},
            {data: 'grade', name: 'grade'},
            {data: 'division', name: 'division'},
            {data: 'ministry', name: 'ministry'},
            {data: 'department', name: 'department'},
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
