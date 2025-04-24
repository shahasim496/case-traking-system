@extends('layouts.admin_main')
@section('title','Dashboard')
@section('breadcrumb','Applications Stats')

@section('content')

<div class="my-app">
                    <div class="row pt-1 cards-detail">


                     	<div class="col-lg-3 col-md-3">
                     	   <div class="media-bg">
                     	      <div class="media flex-column flex-md-row">
                     	         <img src="/assets/img/total.png" alt="#">
                     	         <div class="media-body align-self-center">
                     	            <h5>All</h5>
                     	            <p>{{$data['all']}}</p>
                     	         </div>
                     	      </div>

                     	   </div>
                     	</div>

                         <div class="col-lg-3 col-md-3">
                     	   <div class="media-bg approve-bg">
                     	      <div class="media flex-column flex-md-row">
                     	         <img src="/assets/img/approve.png" alt="#">
                     	         <div class="media-body align-self-center">
                     	            <h5>Approved</h5>
                     	            <p class="approve">{{$data['approve']}}</p>
                     	         </div>
                     	      </div>


                     	  </div>
                     	</div>

                     	<div class="col-lg-3 col-md-3">
                     	   <div class="media-bg in_process-bg">
                     	      <div class="media flex-column flex-md-row">
                     	         <img src="/assets/img/inprocess.png" alt="#">
                     	         <div class="media-body align-self-center">
                     	            <h5>In Process</h5>
                     	            <p class="in_process">{{$data['in_process']}}</p>
                     	         </div>
                     	      </div>

                     	   </div>
                     	</div>

                         <div class="col-lg-3 col-md-3">
                     	   <div class="media-bg reject-bg">
                     	      <div class="media flex-column flex-md-row">
                     	         <img src="/assets/img/dropped.png" alt="#">
                     	         <div class="media-body align-self-center">
                     	            <h5>Rejected</h5>
                     	            <p class="reject">{{$data['reject']}}</p>
                     	         </div>
                     	      </div>

                     	   </div>
                     	</div>



                    </div>
                 </div>

                  <div class="page-title-breadcrumb mt-3">
                     <div class="pull-left">
                        <h1 class="page-title pb-2">New Applications</h1>
                     </div>
                  </div>
<table id="table_id" class="display">
<thead>
<tr>
    <th>Sr.</th>
    <th>User Name</th>
    <th>Title</th>
    <th>Category</th>
    <th>Submission Date</th>
    <th>Status</th>
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
        ajax: "{{ route('applications') }}",
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'user', name: 'user'},
            {data: 'title', name: 'title'},
            {data: 'category', name: 'category'},
            {data: 'submittion_date', name: 'submittion_date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ]
    });

  });
</script>

@endsection
