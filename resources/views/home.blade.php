@extends('layouts.main')
@section('title','Dashboard')
@section('breadcrumb','Welcome to HRMIS,')

@section('content')

<div class="row">
<div class="col-lg-6">
<a href="{{route('apply.noc')}}">
<div class="media flex-column flex-md-row media-bg">
    <img src="/assets/img/application.png" alt="#">
    <div class="media-body align-self-center">
        <h4>Apply for HRMIS</h4>
    </div>
</div>
</a>
</div>
<div class="col-lg-6">
<a href="{{route('certificates')}}">
<div class="media flex-column flex-md-row media-bg">
    <img src="/assets/img/certificate.png" alt="#">
    <div class="media-body align-self-center">
        <h4>Certificates</h4>
    </div>
</div>
</a>
</div>
</div>
<div class="page-title-breadcrumb mt-3">
<div class="pull-left">
<h1 class="page-title pb-2">My Application Listings</h1>
</div>
</div>
<table id="table_id" class="display">
<thead>
<tr>
    <th>Sr.</th>
    <th>Officer Name</th>
    <th>Officer Group Service</th>
    <th>Officer Batch</th>
    <th>Officer Current Post</th>
    <th>Posting Date</th>
    <th>Current Grade</th>
    <th>Action</th>
</tr>
</thead>
<tbody>


</tbody>
</table>

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
        ajax: "{{ route('officer.getOfficers') }}",
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'officer_name', name: 'officer_name'},
            {data: 'group_service', name: 'group_service'},
            {data: 'officer_batch', name: 'officer_batch'},
            {data: 'current_post', name: 'current_post'},
            {data: 'posting_date', name: 'posting_date'},
            {data: 'current_grade', name: 'current_grade'},
            {data: 'action', name: 'action'},
        ]
    });

  });
</script>

@endsection
