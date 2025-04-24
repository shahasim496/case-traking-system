@extends('layouts.main')
@section('title','Dashboard')
@section('breadcrumb','Welcome')

@section('content')


    <div class=" affix-row global-main">
        <div class="col-sm-12 col-md-12">
           <h4>Roles & Permission   <a href="javascript:void(0)" data-toggle="modal" data-target=".addRole" class="btn " style="border-radius:50%; padding:5px; color:white; background-color:#016528; border:3px solid white; width:47px; font-size:20px"  ><i class="fa fa-plus"></i></a></h4>
            <div class="">

                <form class="pt-4" method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="mivkBIQxuVZUWsYl1QwKaJDG88zsOatqj8TrRXmC">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select data-placeholder="Please Select Role to assign Permissions" data-allow-clear="1" name="role_id" id="role_id">
                                    <option></option>
                                    @foreach($roles as $key => $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 offset-2">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->has('name'))
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            <strong>Error!</strong> {{ $errors->first('name') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="roles-permission">
        <div class="col-12 pt-3">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                       aria-controls="pills-home" aria-selected="true">Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                       aria-controls="pills-profile" aria-selected="false">Services</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  {{--  <div class="mt-3">
                        <h6>Modules</h6>
                    </div>--}}
                    <div class="row mb-4">

                        <div class="col-lg-12 col-md-12 col-12 ">
                            <div class="card toggle-radio p-0 pl-2 mt-3">

                                <div class="d-flex justify-content-between">
                                    <div class="p-2"><label for="">Officer</label></div>
                                    <div class="p-2">
                                        <div class="switchToggle float-right">
                                            <input type="checkbox" data-toggle="collapse"
                                                   data-target="#collapse-officer" id="switch-officer">
                                            <label for="switch-officer">Toggle</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="collapse-officer" class="collapse">
                                    <hr class="dashed mt-0">
                                    <div class="row pl-3 pr-3" >
                                        @foreach($modulePermissions->unique('sub_module') as $key => $modulePermission)
                                            <div class="col-4 mb-2">
                                                <label for="">{{ $modulePermission->sub_module }}</label>
                                                <div class="switchToggle float-right">
                                                    <input type="checkbox" class="module_permission" id="module-{{ str_replace(' ','-', $modulePermission->sub_module) }}">
                                                    <label for="module-{{ str_replace(' ','-', $modulePermission->sub_module) }}">Toggle</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="dashed mt-0">

                                <div class="d-flex justify-content-between">
                                    <div class="p-2"><label for="">Dashboard</label></div>
                                    <div class="p-2">
                                        <div class="switchToggle float-right">
                                            <input type="checkbox" data-toggle="collapse"
                                                   class = "side_module_permission"
                                                   data-permission="Read Dashboard"
                                                   data-module="Dashboard"
                                                   data-target="#collapse-dashboard" id="switch-dashboard">
                                            <label for="switch-dashboard">Toggle</label>
                                        </div>
                                    </div>
                                </div>

                                <hr class="dashed mt-0">

                                <div class="d-flex justify-content-between">
                                    <div class="p-2"><label for="">Reporting</label></div>
                                    <div class="p-2">
                                        <div class="switchToggle float-right">

                                            <input type="checkbox" data-toggle="collapse"
                                                   data-permission="Read Report"
                                                   data-module="Reporting"
                                                   data-target="#collapse-reporting" id="switch-reporting">
                                            <label for="switch-reporting">Toggle</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="collapse-reporting" class="collapse">
                                    <hr class="dashed mt-0">
                                    <div class="row pl-3 pr-3" >
                                        @foreach($rerpotPermissions as $key => $rerpotPermission)
                                            <div class="col-4 mb-2">
                                                <label for="">{{ $rerpotPermission->name }}</label>
                                                <div class="switchToggle float-right">
                                                    <input type="checkbox" class="module_reporting" id="module_{{ str_replace(' ','-', $rerpotPermission->name) }}">
                                                    <label for="module_{{ str_replace(' ','-', $rerpotPermission->name) }}">Toggle</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="dashed mt-0">

                                <div class="d-flex justify-content-between">
                                    <div class="p-2"><label for="">Training Type</label></div>
                                    <div class="p-2">
                                        <div class="switchToggle float-right">
                                            <input type="checkbox" data-toggle="collapse"
                                                   class = "training_module_permission"
                                                   data-permission="Read Report"
                                                   data-module="Training Type"
                                                   data-target="#collapse-traning_type" id="switch-traning_type">
                                            <label for="switch-traning_type">Toggle</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="collapse-traning_type" class="collapse">
                                    <hr class="dashed mt-0">
                                    <div class="row pl-3 pr-3" >
                                        @foreach($training_types as $key => $training_type)
                                            <div class="col-4 mb-2">
                                                <label for="">{{ $training_type->name }}</label>
                                                <div class="switchToggle float-right">
                                                    <input type="checkbox" class="module_training_type" id="module-{{ str_replace(' ','-', $training_type->id) }}">
                                                    <label for="module-{{ str_replace(' ','-', $training_type->id) }}">Toggle</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <h6>Module Permission</h6>
                    <div class="table-responsive">
                        <table class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Module</th>
                                    <th>Sub Module</th>
                                    <th>Read</th>
                                    <th>Write</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($modulePermissions->unique('sub_module') as $key => $modulePermission)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $modulePermission->module }}</td>
                                    <td>{{ $modulePermission->sub_module }}</td>

                                    @foreach($modulePermissions->where('sub_module',$modulePermission->sub_module ) as $key => $permission2)
                                        <td><input class=" select_permission module-{{str_replace(' ','-', $modulePermission->sub_module)}}" type="checkbox" id="{{$permission2->name}}" value="{{$permission2->name}}"></td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>



                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="mt-3">
                     {{--   <h6>Services Groups</h6>--}}
                        <div class="card toggle-radio p-3 mt-3">
                            {{--<h6>Services Groups</h6>--}}
                            <div class="row mt-3 pl-2">
                                    @foreach($caderServiceGroups as $key => $serviceGroup)
                                        <div class="col-4 mb-2">
                                        <label for="">{{ $serviceGroup->name }}</label>
                                        <div class="switchToggle float-right">
                                            <input type="checkbox"
                                            class="module_group_services"
                                            data-group="group_service"
                                            id="moduless-{{str_replace(' ','-', $serviceGroup->id)}}">
                                            <label for="moduless-{{str_replace(' ','-', $serviceGroup->id)}}">Toggle</label>
                                        </div>
                                    </div>
                                    @endforeach

                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        {{--                                <h6>Services Groups</h6>--}}


                        @foreach($caderServiceGroups as $key => $serviceGroup)
                            <div class="row">
                                <div class="col-lg-12 col-md-6 col-12 mb-3">
                                    <div class="card pt-3 pb-3">
                                        <h6 class="ml-3">{{ $serviceGroup->name }}</h6>
                                        <hr class="dashed mt-0">

                                        <div class="p-3">
                                            <div class="row pl-3">
                                                @foreach($grades as $g => $grade)
                                                    <div class="col-lg-2 col-md-4 col-6 ">
                                                        <div class="form-check form-check-inline">
                                                            <input
                                                            class=" select_grade moduless-{{str_replace(' ','-', $serviceGroup->id)}}"
                                                            type="checkbox"
                                                            data-group_id="{{$serviceGroup->id}}"
                                                            value="{{$grade->id}}"
                                                            id="{{$serviceGroup->id.'-'.$grade->id}}">
                                                            <label class="form-check-label" for=""> {{ $grade->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade addRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('roles-permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" value="" name="name" id="name" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="Submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@section('jsfile')

    <script src="{{asset('assets/plugins/notify/notify.min.js')}}"></script>
    <script>
        $(function () {
            window.smoothScroll = function (target) {
                var scrollContainer = target;
                do { //find scroll container
                    scrollContainer = scrollContainer.parentNode;
                    if (!scrollContainer) return;
                    scrollContainer.scrollTop += 1;
                } while (scrollContainer.scrollTop == 0);

                var targetY = 0;
                do { //find the top of target relatively to the container
                    if (target == scrollContainer) break;
                    targetY += target.offsetTop;
                } while (target = target.offsetParent);

                scroll = function (c, a, b, i) {
                    i++;
                    if (i > 30) return;
                    c.scrollTop = a + (b - a) / 30 * i;
                    setTimeout(function () {
                        scroll(c, a, b, i);
                    }, 20);
                }
                // start scrolling
                scroll(scrollContainer, scrollContainer.scrollTop, targetY, 0);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let role = $('#role_id').val();

            $('#role_id').on('change', function() {
                role = $('#role_id').val();
                $(".module_permission").prop("checked", false);
                $(".select_permission").prop("checked", false);
                $(".module_group_services").prop("checked", false);
                $(".select_grade").prop("checked", false);

                $("#switch-reporting").prop("checked", false);
                $(".module_reporting").prop("checked", false);

                getRolePermissions(role);
            });

            // module permission
            $('.training_module_permission').change(function(){

                let module = $(this).attr("id");
                let type = "";
                let permissions = "";
                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }
                if ($(this).is(':checked')) {
                    type = "assigned";
                } else {
                    type = "revoked";
                }

                assignRoleTraning(role,type);
            });

            // module on change
            $('.module_training_type').change(function(){

                let module = $(this).attr("id");
                let type = "";
                let permissions = "";
                let group = [$(this).attr("data-group")];

                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                if ($(this).is(':checked')) {
                    $("." + module ).prop("checked", true);
                    var training_type_id = this.id;
                    type = "assigned";
                } else {
                    $("." + module ).prop("checked", false);
                    var training_type_id = this.id;
                    type = "revoked";
                }

                const myArray = training_type_id.split("-");
                var tTypeId = myArray[myArray.length-1];

                assignRevokeTraningTypes(role,type,tTypeId);
            });

            // module on change
            $('.module_reporting').change(function(){

                let module = $(this).attr("id");
                let type = "";
                let permissions = "";
                let group = [$(this).attr("data-group")];

                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                if ($(this).is(':checked')) {
                    $("." + module ).prop("checked", true);
                    var reporting_type = this.id;
                    type = "assigned";
                } else {
                    $("." + module ).prop("checked", false);
                    var reporting_type = this.id;
                    type = "revoked";
                }

                const myArray = reporting_type.split("_");
                var tTypeId = myArray[myArray.length-1];
                tTypeId = tTypeId.replaceAll("-", " ");

                assignRevokeReportingTypes(role,type,tTypeId);
            });

            // module on change
            $('.module_permission').change(function(){

                let module = $(this).attr("id");
                let type = "";
                let permissions = "";
                let group = [$(this).attr("data-group")];

                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                if ($(this).is(':checked')) {
                    $("." + module ).prop("checked", true);
                    permissions = $("." + module + ':checkbox:checked').map(function() {
                        return this.value;
                    }).get();

                    type = "assigned";
                } else {
                    $("." + module ).prop("checked", false);
                    permissions = $("." + module + ':checkbox:not(:checked)').map(function() {
                        return this.value;
                    }).get();
                    type = "revoked";
                }

                assignRevokePermission(permissions, role,type,module, false,group);
            });

            // module permission
            $('.side_module_permission').change(function(){

                let module = $(this).attr("id");
                let type = "";
                let permissions = "";
                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                if ($(this).is(':checked')) {
                    $("." + module ).prop("checked", true);
                    permissions = [$(this).attr("data-permission")];
                    module = $(this).attr("data-module");
                    type = "assigned";

                } else {
                    $("." + module ).prop("checked", false);
                    // permissions[] = $(this).attr("data-permission");
                    permissions = [$(this).attr("data-permission")];
                    module = $(this).attr("data-module");
                    type = "revoked";
                }

                assignRevokePermission(permissions, role,type,module, false,'');
            });//end of function

            $('#switch-reporting').change(function(){

                let module = $(this).attr("id");
                let type = "";
                let permissions = "";
                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                if ($(this).is(':checked')) {
                    $("." + module ).prop("checked", true);
                    permissions = [$(this).attr("data-permission")];
                    module = $(this).attr("data-module");
                    type = "assigned";

                } else {
                    $("." + module ).prop("checked", false);
                    // permissions[] = $(this).attr("data-permission");
                    permissions = [$(this).attr("data-permission")];
                    module = $(this).attr("data-module");
                    type = "revoked";
                }

                assignRevokePermission(permissions, role,type,module, false,'');
            });//end of function

            // permission on change
            $('.select_permission').change(function(){

                let value = $(this).val();
                module = value.split(' ');
                module_name = module[1].toLowerCase();
                let permissions = [];
                permissions.push(value);
                console.log(permissions);
                let type = ""
                if ($(this).is(':checked')) {
                    type = "assigned";
                    console.log("on " + value);
                } else {
                    console.log("off " + value);
                    type = "revoked";
                }

                let group = [$(this).attr("data-group")];
                assignRevokePermission(permissions, role,type,module_name, true,group)
            });

            var group_service = '';

            // group on change
            $('.module_group_services').change(function(){

                let module = $(this).attr("id");
                let group_service = $(this).attr("id");
                let group = [$(this).attr("data-group")];

                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                if ($(this).is(':checked')) {
                    $("." + module ).prop("checked", true);
                    permissions = $("." + module + ':checkbox:checked').map(function() {
                        return this.value;
                    }).get();

                    type = "assigned";
                } else {
                    $("." + module ).prop("checked", false);
                    permissions = $("." + module + ':checkbox:not(:checked)').map(function() {
                        return this.value;
                    }).get();
                    type = "revoked";
                }

                var group_service_id = group_service.split("-");
                var gServiceId = group_service_id[1];

                assignServicePermissions(permissions,role,type,module,'module_group_services',gServiceId);
            });//end of function

            // permission on change
            $('.select_grade').change(function(){

                let value = $(this).val();

                if (role == "") {
                    $.notify("Please select role first.", 'error');
                    return false;
                }

                let gServiceId = $(this).attr("data-group_id");
                module = 'module-'+gServiceId;

                let type = ""

                if ($(this).is(':checked')) {
                    permissions = $("." + module + ':checkbox:checked').map(function() {
                        return this.value;
                    }).get();

                    type = "assigned";
                } else {
                    permissions = $("." + module + ':checkbox:checked').map(function() {
                        return this.value;
                    }).get();
                    type = "revoked";
                }

                assignServicePermissions(permissions, role,type,module,'select_grade',gServiceId)
            });//end of function

            if(role){
                getRolePermissions(role);
            }

            @if (session('roleSuccess'))
            $.notify("{{ session('roleSuccess') }}",'success');
            @endif

        });

        function getRolePermissions(role) {
            let  isOfficerSelected = false;
            let  isReportingSelected = false;
            let  isDashboardSelected = false;
            $.ajax({
                method: "GET",
                url: "{{route('roles-permissions.index')}}" +'/'+ role,
                success: function(response) {

                    console.log(response);
                    if (response.status == true) {
                        let modules = [];
                        response.data.forEach(function(value, key) {

                            if(value.module == 'Reporting'){
                                var reporting_name = value.name.replace(/\s+/g, '-');
                                var reporting_id = 'module_'+reporting_name;
                                $("input[type=checkbox][id='" + reporting_id + "']").prop("checked", true);
                            }

                            $("input[type=checkbox][value='" + value.name + "']").prop("checked", true);
                            sub_module = value.sub_module.replace(/\s+/g, '-')
                            modules.push(sub_module.replace('&', '-'));

                            if(value.module == 'Officer' &&  isOfficerSelected == false){
                                isOfficerSelected = true;
                                $('#switch-officer').prop("checked", true).prop("aria-expanded", true).removeClass("collapsed");
                                $('#collapse-officer').addClass("show");
                            }

                            if(value.module == 'Reporting' &&  isReportingSelected == false){
                                isReportingSelected = true;
                                // $('#switch-reporting').prop("checked", true);
                                $('#switch-reporting').prop("checked", true).prop("aria-expanded", true).removeClass("collapsed");
                                $('#collapse-reporting').addClass("show");
                            }

                            if(value.module == 'Dashboard' &&  isDashboardSelected == false){
                                isDashboardSelected = true;
                                $('#switch-dashboard').prop("checked", true);
                            }
                        });

                        response.role_group_servies.forEach(function(value, key) {

                            $('#moduless-'+value.group_service_id).prop("checked", true);

                            value.grades.forEach(function(grade, key){

                                var gradeId = value.group_service_id+'-'+grade;
                                $('#'+gradeId).prop("checked", true);
                            });
                        });

                        $('#switch-traning_type').prop("checked", false);
                        if(response.role.is_training_enabled == 1){
                            $('#switch-traning_type').prop("checked", true);

                            $('#switch-traning_type').prop("checked", true).prop("aria-expanded", true).removeClass("collapsed");
                            $('#collapse-traning_type').addClass("show");
                        }

                        response.role_training_types.forEach(function(value, key) {
                            $('#module-'+value.training_type_id).prop("checked", true);
                        });

                        if(!isOfficerSelected){
                            $('#switch-officer').prop("checked", false).prop("aria-expanded", false).addClass("collapsed");
                            $('#collapse-officer').removeClass("show");
                        }

                        if(!isReportingSelected){
                            // $('#switch-reporting').prop("checked", false);
                            $('#switch-reporting').prop("checked", false).prop("aria-expanded", false).addClass("collapsed");
                            $('#collapse-reporting').removeClass("show");
                        }

                        let unique_modules = modules.filter(function(itm, i, a) {
                            return i == a.indexOf(itm);
                        });

                        unique_modules.forEach(function(item) {
                            $("#module-" + item).prop("checked", true);
                        });

                    }
                }
            });
        }

        function assignRevokePermission(permissions, role,type,module, callAgain,group){
            $.ajax({
                method: "PATCH",
                url: "{{route('roles-permissions.index')}}" +'/'+ role,
                data: {
                    permissions: permissions,
                    role: role,
                    type: type,
                    group: group,
                    module: module,
                    method : 'PUT'
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == true) {
                        /* if(callAgain){
                             getRolePermissions(role);
                         }*/
                        $.notify("Permissions " + type + " successfully.", 'success');
                    }
                }
            });
        }//endof function

        function assignServicePermissions(permissions, role,type,module, callAgain,group){
            $.ajax({
                method: "POST",
                url: "{{route('roles-permissions.updateServices','"+role+"')}}" ,
                data: {
                    permissions: permissions,
                    role: role,
                    type: type,
                    group_service_id: group,
                    select_type: callAgain,
                    module: module,
                    method : 'PUT'
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == true) {
                        /* if(callAgain){
                             getRolePermissions(role);
                         }*/
                        $.notify("Training type " + type + " successfully.", 'success');
                    }
                }
            });
        }

        function assignRevokeTraningTypes(role,type,training_type_id){
            console.log("assignRevokeTraningTypes");

            $.ajax({
                method: "POST",
                url: "{{route('roles-permissions.updateTrainingTypes','"+role+"')}}",
                data: {
                    role: role,
                    type: type,
                    training_type_id: training_type_id,
                    method : 'PUT'
                },
                success: function(response) {
                    if (response.status == true) {
                        $.notify("Training type " + type + " successfully.", 'success');
                    }
                }
            });
        }//endof function

        function assignRevokeReportingTypes(role,type,permissions){

            var url = '{{ route("roles-permissions.updateRoleReporting", ":id") }}';
            url = url.replace(':id', role);

            $.ajax({
                method: "POST",
                enctype: 'multipart/form-data',
                url:url,
                data: {
                    role: role,
                    type: type,
                    permissions: permissions,
                    method : 'PUT'
                },
                success: function(response) {

                    if (response.status == true) {
                        $.notify("Reporting type " + type + " successfully.", 'success');
                    }
                }
            });
        }//endof function

        function assignRoleTraning(role,type){

            $.ajax({
                method: "POST",
                url: "{{route('roles-permissions.updateRoleTraining','"+role+"')}}",
                data: {
                    role: role,
                    type: type,
                    method : 'PUT'
                },
                success: function(response) {
                    if (response.status == true) {
                        $.notify("Permissions " + type + " successfully.", 'success');
                    }
                }
            });
        }//endof function
    </script>

@endsection
