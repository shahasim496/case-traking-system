@extends('layouts.main')
@section('title','Customizable Report')
@section('breadcrumb','Customizable Report')

@section('content')

<div class="row affix-row global-main">

    <div class="col-sm-12 col-md-12">
        <div class="affix-content h-100">

            <form class="pt-4" method="POST" action="" enctype='multipart/form-data'>
                @csrf

                <div class="row">
                    <div class="col-1-5">
                        <div class="form-group">
                            <label for="" class="label-font">Officer Name</label>
                            <input type="text" class="form-control" placeholder="Type officer name" value="{{old('officer_name')}}" name="officer_name" id="officer_name">

                        </div>
                    </div>

                    <div class="col-1-5">
                        <div class="form-group">
                            <label for="" class="label-font">Cadre</label>
                            <select data-placeholder="Select Cadre" data-allow-clear="1" name="group_service_id[]" multiple="multiple" id="group_service_id">
                                @foreach($group_services as $gs=>$group_service)
                                <option value="{{$group_service->id}}" {{$group_service_id == $group_service->id ? 'selected' : ''}}>{{$group_service->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-1-5 ">
                        <div class="form-group">
                            <label for="" class="label-font">Domicile</label>
                            <select data-placeholder="Select Domicile" data-allow-clear="1" name="domicile_id[]" multiple="multiple" id="domicile_id">
                                @foreach($domiciles as $d=>$domicile)
                                <option value="{{$domicile->id}}" {{ in_array($domicile->id, $selected_provinces) ? 'selected' : ''}}>{{$domicile->name}}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-1-5 ">
                        <div class="form-group">
                            <label for="" class="label-font">Language</label>
                            <select data-placeholder="Select Language" data-allow-clear="1" name="language_id[]" multiple="multiple" id="language_id">
                                @foreach($domiciles as $d=>$domicile)
                                <option value="{{$domicile->id}}">{{$domicile->name}}</option>
                                @endforeach
                            </select>


                        </div>

                    </div>
                    <div class="col-1-5 d-flex pt-4 text-right pr-3">
                        <div class="d-flex">
                        <a style="height:37px;background-color: #aee2c2; color: #046529 !important; width:33px; display:flex ; margin-right:6px; justify-content:center ; align-items:center" href="javascript:void(0)" id="advance_search" class="btn btn-angle"> <i id="advance_search_icon" class="fa fa-angle-double-down" aria-hidden="true"></i></a>
                        <a style="height:37px;background-color: #aee2c2;color: #046529 !important; width:33px; margin-right:6px ; display:flex; justify-content:center; align-items:center" href="javascript:void(0)" id="btn_excel" class="btn btn-angle"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                        <button style="height:37px; display:flex; justify-content:center ; align-items:center" type="button" id="search" role="button" class="btn btn-primary">Search</button>
                        </div>


                    </div>

                </div>

                <div class="row advance_div ">

                    <div class="col-lg-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Present Grade</label>
                            <select data-placeholder="Select Grade" multiple="multiple" data-allow-clear="1" name="grade_id[]" id="grade_id">
                                @foreach($grades as $g=>$grade)
                                <option value="{{$grade->id}}" {{$grade_id == $grade->id ? 'selected' : ''}}>{{$grade->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Domicile District</label>
                            <select data-placeholder="Select Domicile" data-allow-clear="1" name="domicile_district_id[]" multiple="multiple" id="domicile_district_id">
                                @foreach($domicile_districts as $d=>$domicile_district)
                                <option value="{{$domicile_district->id}}">{{$domicile_district->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Posting Ministry</label>
                            <select data-placeholder="Select Ministry" data-allow-clear="1" name="ministry_id[]" multiple="multiple" id="ministry_id">
                                @foreach($ministories as $gs=>$ministory)
                                <option value="{{$ministory->id}}">{{$ministory->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                </div>

                <div class="row advance_div">

                    <div class="col-lg-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Posting Division</label>
                            <select data-placeholder="Select Division" data-allow-clear="1" name="division_id[]" multiple="multiple" id="division_id">
                                @foreach($divisions as $g=>$division)
                                <option value="{{$division->id}}">{{$division->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Department</label>
                            <select data-placeholder="Select Department" data-allow-clear="1" name="department_id[]" multiple="multiple" id="department_id">
                                @foreach($departments as $d=>$department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Post</label>
                            <select data-placeholder="Select Post" data-allow-clear="1" name="station_id[]" multiple="multiple" id="station_id">
                                @foreach($stations as $g=>$station)
                                <option value="{{$station->id}}">{{$station->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                </div>

                <div class="row advance_div">

                    <div class="col-lg-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Qualification</label>
                            <select data-placeholder="Select Qualification" data-allow-clear="1" name="qualification_id[]" multiple="multiple" id="qualification_id">
                                @foreach($qualifications as $gs=>$qualification)
                                <option value="{{$qualification->id}}">{{$qualification->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Specialization</label>
                            <input type="text" class="form-control"
                            placeholder="Type specialization"
                            value="{{old('specialization')}}"
                            name="specialization" id="specialization">
                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Religion</label>
                            <select data-placeholder="Select Religion" data-allow-clear="1" name="religion_id[]" multiple="multiple" id="religion_id">
                                @foreach($religions as $or=>$religion)
                                <option value="{{$religion->id}}">{{$religion->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>



                </div>

                <div class="row advance_div">

                    <div class="col-lg-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Batch / CTP</label>
                            <input type="text" class="form-control" placeholder="Type officer Batch No" value="" name="batch_id" id="batch_id">


                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Other Nationality</label>
                            <select data-placeholder="Select Other Nationality" data-allow-clear="1" name="other_nationality[]" multiple="multiple" id="other_nationality">
                                @foreach($countries as $c=>$country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Personal Number</label>
                            <input type="text" class="form-control" placeholder="Type personal number" value="{{old('p_no')}}" name="p_no" id="p_no">
                        </div>
                    </div>

                </div>

                <div class="row advance_div">

                    <div class="col-lg-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                        <label for="" class="label-font">Posting Province</label>
                        <select data-placeholder="Select Province" data-allow-clear="1" name="posting_domicile_id[]" multiple="multiple" id="posting_domicile_id">
                            @foreach($domiciles as $d=>$domicile)
                            <option value="{{$domicile->id}}" {{ in_array($domicile->id, $selected_provinces) ? 'selected' : ''}}>{{$domicile->name}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Posting District</label>
                            <select data-placeholder="Select District" data-allow-clear="1" name="posting_domicile_district_id[]" multiple="multiple" id="posting_domicile_district_id">
                                @foreach($domicile_districts as $d=>$domicile_district)
                                <option value="{{$domicile_district->id}}">{{$domicile_district->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-4 col-12 col-md-4 ">
                        <div class="form-group">
                            <label for="" class="label-font">Cadre/Ex-Cadre</label>
                            <select data-placeholder="Select Cadre/Ex-Cadre" data-allow-clear="1" name="cader_id[]" multiple="multiple" id="cader_id">
                                @foreach($caders as $c=>$cader)
                                <option value="{{$cader->id}}">{{$cader->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                </div>

                <div class="row advance_div">
                    <div class="col-lg-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Gender</label>
                            <select data-placeholder="Select Gender" data-allow-clear="1" name="gender_id[]" multiple="multiple" id="gender_id">
                                @foreach($genders as $og=>$gender)
                                <option value="{{$gender->id}}">{{$gender->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Posting Type</label>
                            <select data-placeholder="Select Posting Type" data-allow-clear="1"
                            name="posting_type_id[]" multiple="multiple" id="posting_type_id">
                                @foreach($post_types as $pt=>$post_type)
                                <option value="{{$post_type->id}}">{{$post_type->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4 col-12 col-md-4 pl-1">
                        <div class="form-group">
                            <label for="" class="label-font">Status of Employee</label>
                            <select data-placeholder="Select Status of Employee" data-allow-clear="1"
                            name="employee_status_id[]" multiple="multiple" id="employee_status_id">
                                @foreach($statues as $s=>$status)
                                <option value="{{$status->id}}">{{$status->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                </div>

            </form>

            <!-- <div class="d-inline mt-3 float-right mb-3">
            <a href="{{route('report.officers')}}" class="btn btn-secondary">Reset</a>
            <button type="button" id="search" role="button" class="btn btn-save">Search</button>
            <a href="javascript:void(0)" id="advance_search" class="btn btn-primary" >Advance Search</a>
        </div> -->


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
                    <th>CNIC</th>
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
    var officer_name = null;
    var group_name = '{{$group_name}}';
    var grade_name = '{{$grade_name}}';
    var group_id = '{{$group_service_id}}';
    var g_id = '{{$grade_id}}';
    var p_id = '{{$province_id}}';
    var isOpen = '{{$isOpen}}';

    var selected_provinces = '{{json_encode($selected_provinces)}}';
    selected_provinces = JSON.parse(selected_provinces);

    var group_service_id = group_id ? [group_id] : null;
    var grade_id = null;

    if (g_id) {
        grade_id = [g_id];
    }

    console.log(grade_id);
    //var province_id = p_id ? [p_id] : null;
    var province_id = selected_provinces ? selected_provinces : null;
    var domicile_id = null;
    var posting_domicile_id = null;
    var language_id = null;
    var ministry_id = null;
    var division_id = null;
    var department_id = null;
    // var province_id = null;
    var domicile_district_id = null;
    var posting_domicile_district_id = null;
    var station_id = null;
    var qualification_id = null;
    var specialization = null;
    var gender_id = null;
    var religion_id = null;
    var batch_id = null;
    var other_nationality = null;
    var cader_id = null;
    var p_no = null;

    var employee_status_id = null;
    var posting_type_id = null;

    $('select').on('change', function() {
        if (this.id == 'group_service_id') {
            group_service_id = this.value;
        } else if (this.id == 'grade_id') {
            grade_id = this.value;
        } else if (this.id == 'domicile_id') {
            domicile_id = this.value;
        } else if (this.id == 'station_id') {
            station_id = this.value;
        } else if (this.id == 'ministry_id') {
            ministry_id = this.value;
        } else if (this.id == 'division_id') {
            division_id = this.value;
        } else if (this.id == 'department_id') {
            department_id = this.value;
        } else if (this.id == 'province_id') {
            province_id = this.value;
        } else if (this.id == 'language_id') {
            language_id = this.value;
        } else if (this.id == 'domicile_district_id') {
            domicile_district_id = this.value;
        } else if (this.id == 'gender_id') {
            gender_id = this.value;
        } else if (this.id == 'religion_id') {
            religion_id = this.value;
        } else if (this.id == 'batch_id') {
            batch_id = this.value;
        } else if (this.id == 'other_nationality') {
            other_nationality = this.value;
        } else if (this.id == 'cader_id') {
            cader_id = this.value;
        } else if (this.id == 'posting_domicile_id') {
            posting_domicile_id = this.value;
        } else if (this.id == 'posting_domicile_district_id') {
            posting_domicile_district_id = this.value;
        } else if (this.id == 'employee_status_id') {
            employee_status_id = this.value;
        } else {
            qualification_id = this.value;
        }
    }); //end of select change

    $(function() {

        table.destroy();
        table = $('#table_id').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            stateSave: true,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            bFilter: true,
            order: [
                [5, "desc"]
            ],
            // deferLoading: 100,
            // ajax: "{{ route('officer.getOfficers') }}",
            ajax: {
                url: "{{ route('report.getOfficerRecords') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
                type: 'POST',
                data: function(d) {
                    d._token="{{csrf_token()}}";
                    d.officer_name = officer_name;
                    d.group_service_id = group_service_id;
                    d.grade_id = grade_id;
                    d.domicile_id = domicile_id;
                    d.ministry_id = ministry_id;
                    d.division_id = division_id;
                    d.department_id = department_id;
                    d.province_id = province_id;
                    d.station_id = station_id;
                    d.language_id = language_id;
                    d.qualification_id = qualification_id;
                    d.specialization = specialization;
                    d.domicile_district_id = domicile_district_id;
                    d.gender_id = gender_id;
                    d.religion_id = religion_id;
                    d.batch_id = batch_id;
                    d.other_nationality = other_nationality;
                    d.cader_id = cader_id;
                    d.p_no = p_no;
                    d.posting_domicile_id = posting_domicile_id;
                    d.posting_domicile_district_id = posting_domicile_district_id;

                    d.employee_status_id = employee_status_id;
                    d.posting_type_id = posting_type_id;
                }
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        },
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        },
                        columns: [0, 1, 2, 3, 4, 5, 6,7,8]
                    }
                },
            ],
            "order": [],
            'columnDefs': [{
                'orderable': false,
                'targets': 0
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'officer_name',
                    name: 'officer_name'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'p_no',
                    name: 'p_no'
                },
                {
                    data: 'group_service',
                    name: 'group_service'
                },
                // {data: 'officer_batch', name: 'officer_batch'},
                {
                    data: 'current_post',
                    name: 'current_post'
                },
                {
                    data: 'current_area',
                    name: 'current_area'
                },
                {
                    data: 'posting_date',
                    name: 'posting_date'
                },
                {
                    data: 'current_grade',
                    name: 'current_grade'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        }); //end of datatable

        $('#table_id_filter input').unbind();
        $('#table_id_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                // table.fnFilter($(this).val());
                table.search($(this).val()).draw();
            }
        });

    }); //end of function

    $(function() {

        $('#department_id').select2('destroy');
        $('#department_id').empty();
        $('#department_id').select2({

            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: true,
            ajax: {
                url: "{{route('getModelData')}}",
                data: function(params) {
                    var query = {
                        department: params.term,
                        type: 'department'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }

        }); //end of select2

        $('#station_id').select2('destroy');
        $('#station_id').empty();
        $('#station_id').select2({

            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: true,
            ajax: {
                url: "{{route('getModelData')}}",
                data: function(params) {
                    var query = {
                        post: params.term,
                        type: 'post'
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }

        }); //end of select2

        $('#division_id').select2('destroy');
        $('#division_id').empty();
        $('#division_id').select2({

            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: true,
            ajax: {
                url: "{{route('getModelData')}}",
                data: function(params) {
                    var query = {
                        division: params.term,
                        type: 'division'
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }

        }); //end of select2

        $('#qualification_id').select2('destroy');
        $('#qualification_id').empty();
        $('#qualification_id').select2({

            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: true,
            ajax: {
                url: "{{route('getModelData')}}",
                data: function(params) {
                    var query = {
                        qualification: params.term,
                        type: 'qualification'
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }

        }); //end of select2

        $('#language_id').select2('destroy');
        $('#language_id').empty();
        $('#language_id').select2({

            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: true,
            ajax: {
                url: "{{route('getModelData')}}",
                data: function(params) {
                    var query = {
                        language: params.term,
                        type: 'language'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }

        }); //end of select2

        $('#other_nationality').select2('destroy');
        $('#other_nationality').empty();
        $('#other_nationality').select2({

            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: true,
            ajax: {
                url: "{{route('getModelData')}}",
                data: function(params) {
                    var query = {
                        country: params.term,
                        type: 'country'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }

        }); //end of select2

    }); //end of function

    $("#search").click(function(e) {

        officer_name = $("#officer_name").val();
        group_service_id = $("#group_service_id").val();
        grade_id = $("#grade_id").val();
        domicile_id = $("#domicile_id").val();
        ministry_id = $("#ministry_id").val();
        division_id = $("#division_id").val();
        department_id = $("#department_id").val();
        province_id = $("#province_id").val();
        station_id = $("#station_id").val();
        language_id = $("#language_id").val();
        qualification_id = $("#qualification_id").val();
        specialization = $("#specialization").val();
        domicile_district_id = $("#domicile_district_id").val();
        gender_id = $("#gender_id").val();
        religion_id = $("#religion_id").val();
        batch_id = $("#batch_id").val();
        other_nationality = $("#other_nationality").val();
        cader_id = $("#cader_id").val();
        p_no = $("#p_no").val();
        posting_domicile_district_id = $("#posting_domicile_district_id").val();
        posting_domicile_id = $("#posting_domicile_id").val();

        employee_status_id = $("#employee_status_id").val();
        posting_type_id = $("#posting_type_id").val();

        table.draw();
        e.preventDefault();

    }); //end of function

    $("#btn_excel").click(function(e) {

        officer_name = $("#officer_name").val();
        group_service_id = $("#group_service_id").val();
        grade_id = $("#grade_id").val();
        domicile_id = $("#domicile_id").val();
        ministry_id = $("#ministry_id").val();
        division_id = $("#division_id").val();
        department_id = $("#department_id").val();
        province_id = $("#province_id").val();
        station_id = $("#station_id").val();
        language_id = $("#language_id").val();
        qualification_id = $("#qualification_id").val();
        specialization = $("#specialization").val();
        domicile_district_id = $("#domicile_district_id").val();
        gender_id = $("#gender_id").val();
        religion_id = $("#religion_id").val();
        batch_id = $("#batch_id").val();
        other_nationality = $("#other_nationality").val();
        cader_id = $("#cader_id").val();
        p_no = $("#p_no").val();
        posting_domicile_district_id = $("#posting_domicile_district_id").val();
        posting_domicile_id = $("#posting_domicile_id").val();

        employee_status_id = $("#employee_status_id").val();
        posting_type_id = $("#posting_type_id").val();

        var query = {
            _token:"{{csrf_token()}}",
            officer_name : officer_name,
            group_service_id : group_service_id,
            grade_id : grade_id,
            domicile_id : domicile_id,
            ministry_id : ministry_id,
            division_id : division_id,
            department_id : department_id,
            province_id : province_id,
            station_id : station_id,
            language_id : language_id,
            qualification_id : qualification_id,
            specialization : specialization,
            domicile_district_id : domicile_district_id,
            gender_id : gender_id,
            religion_id : religion_id,
            batch_id : batch_id,
            other_nationality : other_nationality,
            cader_id : cader_id,
            p_no : p_no,
            is_excel : 1,
            posting_domicile_id : posting_domicile_id,
            posting_domicile_district_id : posting_domicile_district_id,

            employee_status_id : employee_status_id,
            posting_type_id : posting_type_id,
        }
        var url = "{{ route('report.getOfficerRecords') }}?" + $.param(query)
        window.location = url;
return false;
    }); //end of function

    if (isOpen == 1) {
        $('.advance_div').show();
    } else {
        $('.advance_div').hide();
    }

    $('#advance_search').click(function(e) {

        $("#advance_search_icon").toggleClass("fa-angle-double-up");
        $("#advance_search_icon").toggleClass("fa-angle-double-down");
        $('.advance_div').toggle();
    }); //end of function
</script>

@endsection
