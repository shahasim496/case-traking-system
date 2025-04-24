@extends('layouts.main')
@section('title','Employee Status Report')
@section('breadcrumb','Employee Status Report')


@section('content')

<div class="row affix-row global-main">

    <div class="col-sm-12 col-md-12">
        <div class="affix-content h-100">

            <form class="pt-4" method="POST" action="" enctype='multipart/form-data'>
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">From Date</label>
                            <input type="date" class="form-control select_date" placeholder="Type from_date"
                             name="from_date" id="from_date">

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">To Date</label>
                            <input type="date" class="form-control select_date" placeholder="Type to_date"
                             name="to_date" id="to_date">
                        </div>
                    </div>

                    <div class="col-md-4 pt-4 text-right pr-3">
                        <button type="button" id="search" role="button" class="btn btn-primary">Search</button>
                        <!-- <a id="export_btn" href="javascript:void(0)" class="btn btn-primary">Export</a> -->
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Officer Name</label>
                            <input type="text" class="form-control" placeholder="Type officer name" value="{{old('officer_name')}}" name="officer_name" id="officer_name">

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">P No</label>
                            <input type="text" class="form-control" placeholder="Type p no" value="{{old('p_no')}}" name="p_no" id="p_no">

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Cadre</label>
                            <select data-placeholder="Select Cadre" data-allow-clear="1" name="group_service_id[]" multiple="multiple" id="group_service_id">
                                @foreach($group_services as $gs=>$group_service)
                                <option value="{{$group_service->id}}" >{{$group_service->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Grade</label>
                            <select data-placeholder="Select Grade" multiple="multiple" data-allow-clear="1" name="grade_id[]" id="grade_id">
                                @foreach($grades as $g=>$grade)
                                <option value="{{$grade->id}}" >{{$grade->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Posting Type</label>
                            <select data-placeholder="Select Cadre" data-allow-clear="1" name="posting_type_id" id="posting_type_id">
                                @foreach($post_types as $gs=>$post_type)
                                <option value="{{$post_type->id}}">{{$post_type->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Status of Employee</label>
                            <select data-placeholder="Select Employee Status" multiple="multiple" data-allow-clear="1" name="employee_status_id" id="employee_status_id">
                                @foreach($statues as $s=>$status)
                                <option value="{{$status->id}}">{{$status->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                </div>

            </form>



        </div>
    </div>
</div>

<div class="listing">
    <div class="table-responsive card">


        <table id="table_id" class="display all-user dataTable no-footer">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Officer Name</th>
                    <th>Personal No.</th>
                    <th>Group Service</th>
                    <!-- <th>Batch</th> -->
                    <th>Current Post</th>
                    <th>Min/Div/Dept</th>
                    <th>Posting Date</th>
                    <th>Current Grade</th>
                    <th>View</th>
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

    var from_date = null;
    var to_date = null;
    var posting_type_id = null;
    var officer_name = null;
    var p_no = null;
    var group_service_id = null;
    var grade_id = null;
    var employee_status_id = null;

    $(function () {

    table.destroy();
    table = $('#table_id').DataTable({
        stateSave: true,
        processing: true,
        serverSide: true,
        ordering:  false,
        searching: false,
        paging: true,
        lengthMenu: [ [10,25, 50], [10,25, 50] ],
        ajax: {
            url: "{{ route('report.getReputation') }}",
            data: function(d) {
                d.posting_type_id = posting_type_id;
                d.to_date = to_date;
                d.from_date = from_date;
                d.officer_name = officer_name;
                d.p_no = p_no;
                d.group_service_id = group_service_id;
                d.grade_id = grade_id;
                d.employee_status_id = employee_status_id;
            }
        },
        dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    // title: function(){
                    //     return dText;
                    // },
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        },

                        columns: [0, 1, 2, 3, 4]
                    }
                },

            ],
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'officer_name', name: 'officer_name'},
            {data: 'p_no', name: 'p_no'},
            {data: 'group_service', name: 'group_service'},
            {data: 'current_post', name: 'current_post'},
            {data: 'current_area', name: 'current_area'},
            {data: 'posting_date', name: 'posting_date'},
            {data: 'current_grade', name: 'current_grade'},
            {data: 'action', name: 'action'},
        ]
    });

    $("#search").click(function(e) {

        from_date = $("#from_date").val();
        to_date = $("#to_date").val();
        posting_type_id = $("#posting_type_id").val();

        officer_name = $("#officer_name").val();
        p_no = $("#p_no").val();
        group_service_id = $("#group_service_id").val();
        grade_id = $("#grade_id").val();
        employee_status_id = $("#employee_status_id").val();

        table.draw();
        e.preventDefault();

    });//end of function

    $("#export_btn").click(function(e) {

        var params = '';
        if(group_service_id){
            params = 'group_service_id='+group_service_id+'&';
        }
        if(grade_id){
            params += 'grade_id='+grade_id;
        }

        if(officer_name){
            params += 'officer_name='+officer_name;
        }
        if(p_no){
            params += 'p_no='+p_no;
        }
        if(group_service_id){
            params += 'group_service_id='+group_service_id;
        }
        if(grade_id){
            params += 'grade_id='+grade_id;
        }

        if(employee_status_id){
            params += 'employee_status_id='+employee_status_id;
        }
        window.location.href = downloadReports+'?'+params;
    });

  });//end of function


</script>

@endsection
