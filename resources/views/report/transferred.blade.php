@extends('layouts.main')
@section('title','Transferred Report')
@section('breadcrumb','Transferred Report')


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
                            <label for="" class="label-font">From Posting Ministry</label>
                             <select data-placeholder="Select Ministry" data-allow-clear="1" name="from_ministry_id" id="from_ministry_id">
                                @foreach($ministories as $gs=>$ministory)
                                <option value="{{$ministory->id}}">{{$ministory->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">From Division <span class="text-danger">*</span></label>
                        <?php $division_id = old('from_division_id'); ?>
                        <select data-placeholder="Select Division" data-allow-clear="1"
                        name="from_division_id" id="from_division_id">
                            <option></option>
                            @foreach($divisions as $p=>$division)
                            <option value="{{$division->id}}" {{$division_id == $division->id ? 'selected' : ''}}>{{$division->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('from_division_id'))
                            <span class="text-danger">{{ $errors->first('from_division_id') }}</span>
                        @endif
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">From Department / Office <span class="text-danger">*</span></label>
                            <?php $department_id = old('from_department_id'); ?>
                            <select data-placeholder="Select Department" data-allow-clear="1"
                            name="from_department_id" id="from_department_id">
                                <option></option>
                                @foreach($departments as $p=>$department)
                                <option value="{{$department->id}}" {{$department_id == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('from_department_id'))
                                <span class="text-danger">{{ $errors->first('from_department_id') }}</span>
                            @endif
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">To Posting Ministry</label>
                            <select data-placeholder="Select Ministry" data-allow-clear="1" name="to_ministry_id" id="to_ministry_id">
                                @foreach($ministories as $gs=>$ministory)
                                <option value="{{$ministory->id}}">{{$ministory->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-4 col-12 col-md-4 ">
                    <div class="form-group">
                        <label for="" class="label-font">To Division <span class="text-danger">*</span></label>
                        <?php $division_id = old('to_division_id'); ?>
                        <select data-placeholder="Select Division" data-allow-clear="1"
                        name="to_division_id" id="to_division_id">
                            <option></option>
                            @foreach($divisions as $p=>$division)
                            <option value="{{$division->id}}" {{$division_id == $division->id ? 'selected' : ''}}>{{$division->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('to_division_id'))
                            <span class="text-danger">{{ $errors->first('to_division_id') }}</span>
                        @endif
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">To Department / Office <span class="text-danger">*</span></label>
                            <?php $department_id = old('to_department_id'); ?>
                            <select data-placeholder="Select Department" data-allow-clear="1"
                            name="to_department_id" id="to_department_id">
                                <option></option>
                                @foreach($departments as $p=>$department)
                                <option value="{{$department->id}}" {{$department_id == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('to_department_id'))
                                <span class="text-danger">{{ $errors->first('to_department_id') }}</span>
                            @endif
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Post</label>
                            <select data-placeholder="Select Post" data-allow-clear="1" name="post_id" id="post_id">
                                @foreach($posts as $gs=>$post)
                                <option value="{{$post->id}}">{{$post->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                        <label for="" class="label-font">Province</label>
                        <?php $province_id = old('province_id'); ?>
                        <select data-placeholder="Select Province" data-allow-clear="1"
                        name="province_id" id="province_id">
                            <option></option>
                            @foreach($provinces as $p=>$province)
                            <option value="{{$province->id}}" {{$province_id == $province->id ? 'selected' : ''}}>{{$province->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('province_id'))
                            <span class="text-danger">{{ $errors->first('province_id') }}</span>
                        @endif
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">District</label>
                            <?php $district_id = old('district_id'); ?>
                            <select data-placeholder="Select District" data-allow-clear="1"
                            name="district_id" id="district_id">
                                <option></option>
                                @foreach($districts as $p=>$district)
                                <option value="{{$district->id}}" {{$district_id == $district->id ? 'selected' : ''}}>{{$district->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('district_id'))
                                <span class="text-danger">{{ $errors->first('district_id') }}</span>
                            @endif
                            </div>
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
    var from_ministry_id = null;
    var to_ministry_id = null;
    var ministry_id = null;

    var posting_type_id = null;
    var officer_name = null;
    var p_no = null;
    var group_service_id = null;
    var grade_id = null;
    var employee_status_id = null;

    var from_division_id = null;
    var from_department_id = null;
    var to_division_id = null;
    var to_department_id = null;

    var post_id = null;
    var province_id = null;
    var district_id = null;

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
            url: "{{ route('report.getTransferred') }}",
            data: function(d) {
                d.posting_type_id = posting_type_id;
                d.to_date = to_date;
                d.from_date = from_date;
                d.from_ministry_id = from_ministry_id;
                d.to_ministry_id = to_ministry_id;
                d.ministry_id = ministry_id;

                d.posting_type_id = posting_type_id;
                d.officer_name = officer_name;
                d.p_no = p_no;
                d.group_service_id = group_service_id;
                d.grade_id = grade_id;
                d.employee_status_id = employee_status_id;

                d.from_division_id = from_division_id;
                d.from_department_id = from_department_id;
                d.to_division_id = to_division_id;
                d.to_department_id = to_department_id;

                d.post_id = post_id;
                d.province_id = province_id;
                d.district_id = district_id;
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
        from_ministry_id = $("#from_ministry_id").val();
        to_ministry_id = $("#to_ministry_id").val();

        posting_type_id = $("#posting_type_id").val();
        officer_name = $("#officer_name").val();
        p_no = $("#p_no").val();
        group_service_id = $("#group_service_id").val();
        grade_id = $("#grade_id").val();
        employee_status_id = $("#employee_status_id").val();

        from_department_id = $("#from_department_id").val();
        from_division_id = $("#from_division_id").val();
        to_department_id = $("#to_department_id").val();
        to_division_id = $("#to_division_id").val();

        post_id = $("#post_id").val();
        province_id = $("#province_id").val();
        district_id = $("#district_id").val();

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
        window.location.href = downloadReports+'?'+params;
    });

    $('#from_division_id').select2('destroy');
    $('#from_division_id').empty();
    $('#from_division_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                division: params.term,
                type: 'division'
            }
            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

    $('#from_department_id').select2('destroy');
    $('#from_department_id').empty();
    $('#from_department_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                department: params.term,
                type: 'department'
            }

            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

    $('#to_division_id').select2('destroy');
    $('#to_division_id').empty();
    $('#to_division_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                division: params.term,
                type: 'division'
            }
            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

    $('#to_department_id').select2('destroy');
    $('#to_department_id').empty();
    $('#to_department_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                department: params.term,
                type: 'department'
            }

            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

    $('#post_id').select2('destroy');
    $('#post_id').empty();
    $('#post_id').select2({

    theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect:true,
        ajax: {
            url: "{{route('getModelData')}}",
            data: function (params) {
            var query = {
                post: params.term,
                type: 'post'
            }

            // Query parameters will be ?search=[term]&type=public
            return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
            },
            cache: true
        }

    });//end of select2

  });//end of function


</script>

@endsection
