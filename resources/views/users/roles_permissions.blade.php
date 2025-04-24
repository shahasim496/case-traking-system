@extends('layouts.app')

@section('content')

      <!--begin::Row-->
      <div class="row">
        <div class="col-lg-12">
          <!--begin::Card-->
          <div class="card card-custom gutter-b">
            <div class="card-header">
              <div class="card-title">
                <h3 class="card-label">Roles & Permissions</h3>
              </div>
            </div>
            <div class="card-body marg-20">
              <div class="row">
                <div class="col-lg-4">
                  <a href="javascript:void(0)" data-toggle="modal" data-target=".addRole" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Add Roles</a>

                  <div class="card card-custom gutter-b">
                    <ul class="user-roles">
                      @foreach($roles as $key => $value)
                      <li class="{{$key == 0 ? "active":""}} active_li"><a href="#" class="list_items" data-id="{{$value->id}}" data-name="{{$value->name}}">{{$value->name}}</a></li>
                      @endforeach
                    </ul>

                  </div>

                </div>
                <div class="col-lg-8">
                  <h3 class="card-label">Module Access</h3>
                  <div class="card card-custom gutter-b">

                    <ul class="module-access">

                      <li class="list-group-item">
                        Applications
                        <div class="material-switch float-right">
                          <input id="inventory" type="checkbox" class="module_permission">
                          <label for="inventory" class="badge-success"></label>
                        </div>
                      </li>
                      <li class="list-group-item">
                        Users
                        <div class="material-switch float-right">
                          <input id="meeting" type="checkbox" class="module_permission">
                          <label for="meeting" class="badge-success"></label>
                        </div>
                      </li>
                      <li class="list-group-item">
                        User Roles
                        <div class="material-switch float-right">
                          <input id="stock" type="checkbox" class="module_permission">
                          <label for="stock" class="badge-success"></label>
                        </div>
                      </li>


                    </ul>


                  </div>


                  <table width="100%" border="0" class="display table table-bordered">
                    <thead>
                      <tr>
                        <th>Module Permission</th>
                        <th>View</th>
                        <th>Edit</th>
                        <th>Create</th>
                        <th>Delete</th>
                        <th>Import</th>
                        <th>Export</th>


                      </tr>
                    </thead>
                    <tbody>

                      <tr>
                        <td>User</td>
                        <td align="center"><input value="View User" type="checkbox" checked="checked" class="select_permission user_module"></td>
                        <td align="center"><input value="Edit User" type="checkbox" checked="checked" class="select_permission user_module"></td>
                        <td align="center"><input value="Create User" type="checkbox" checked="checked" class="select_permission user_module"></td>
                        <td align="center"><input value="Delete User" type="checkbox" checked="checked" class="select_permission user_module"></td>
                        <td align="center"><input value="Import User" type="checkbox" checked="checked" class="select_permission user_module"></td>
                        <td align="center"><input value="Delete User" type="checkbox" checked="checked" class="select_permission user_module"></td>
                      </tr>
                      <tr>
                        <td>Applications</td>
                        <td align="center"><input value="View Vehicle" type="checkbox" checked="checked" class="select_permission vehicle_module"></td>
                        <td align="center"><input value="Edit Vehicle" type="checkbox" checked="checked" class="select_permission vehicle_module"></td>
                        <td align="center"><input value="Create Vehicle" type="checkbox" checked="checked" class="select_permission vehicle_module"></td>
                        <td align="center"><input value="Delete Vehicle" type="checkbox" checked="checked" class="select_permission vehicle_module"></td>
                        <td align="center"><input value="Import Vehicle" type="checkbox" checked="checked" class="select_permission vehicle_module"></td>
                        <td align="center"><input value="Delete Vehicle" type="checkbox" checked="checked" class="select_permission vehicle_module"></td>
                      </tr>
                      <tr>
                        <td>Inventory Request</td>
                        <td align="center"><input value="View Inventory" type="checkbox" checked="checked" class="select_permission inventory_module"></td>
                        <td align="center"><input value="Edit Inventory" type="checkbox" checked="checked" class="select_permission inventory_module"></td>
                        <td align="center"><input value="Create Inventory" type="checkbox" checked="checked" class="select_permission inventory_module"></td>
                        <td align="center"><input value="Delete Inventory" type="checkbox" checked="checked" class="select_permission inventory_module"></td>
                        <td align="center"><input value="Import Inventory" type="checkbox" checked="checked" class="select_permission inventory_module"></td>
                        <td align="center"><input value="Delete Inventory" type="checkbox" checked="checked" class="select_permission inventory_module"></td>
                      </tr>
                      <tr>
                        <td>Meeting</td>
                        <td align="center"><input value="View Meeting" type="checkbox" checked="checked" class="select_permission meeting_module"></td>
                        <td align="center"><input value="Edit Meeting" type="checkbox" checked="checked" class="select_permission meeting_module"></td>
                        <td align="center"><input value="Create Meeting" type="checkbox" checked="checked" class="select_permission meeting_module"></td>
                        <td align="center"><input value="Delete Meeting" type="checkbox" checked="checked" class="select_permission meeting_module"></td>
                        <td align="center"><input value="Import Meeting" type="checkbox" checked="checked" class="select_permission meeting_module"></td>
                        <td align="center"><input value="Delete Meeting" type="checkbox" checked="checked" class="select_permission meeting_module"></td>
                      </tr>
                      <tr>
                        <td>Stock</td>
                        <td align="center"><input value="View Stock" type="checkbox" checked="checked" class="select_permission stock_module"></td>
                        <td align="center"><input value="Edit Stock" type="checkbox" checked="checked" class="select_permission stock_module"></td>
                        <td align="center"><input value="Create Stock" type="checkbox" checked="checked" class="select_permission stock_module"></td>
                        <td align="center"><input value="Delete Stock" type="checkbox" checked="checked" class="select_permission stock_module"></td>
                        <td align="center"><input value="Import Stock" type="checkbox" checked="checked" class="select_permission stock_module"></td>
                        <td align="center"><input value="Delete Stock" type="checkbox" checked="checked" class="select_permission stock_module"></td>
                      </tr>
                      <tr>
                        <td>Roles</td>
                        <td align="center"><input value="View Roles" type="checkbox" checked="checked" class="select_permission role_module"></td>
                        <td align="center"><input value="Edit Role" type="checkbox" checked="checked" class="select_permission role_module"></td>
                        <td align="center"><input value="Create Role" type="checkbox" checked="checked" class="select_permission role_module"></td>
                        <td align="center"><input value="Delete Role" type="checkbox" checked="checked" class="select_permission role_module"></td>
                        <td align="center"><input value="Import Roles" type="checkbox" checked="checked" class="select_permission role_module"></td>
                        <td align="center"><input value="Delete Roles" type="checkbox" checked="checked" class="select_permission role_module"></td>
                      </tr>
                      <tr>
                        <td>Permissions</td>
                        <td align="center"><input value="View Permissions" type="checkbox" checked="checked" class="select_permission permission_module"></td>
                        <td align="center"><input value="Edit Permission" type="checkbox" checked="checked" class="select_permission permission_module"></td>
                        <td align="center"><input value="Create Permission" type="checkbox" checked="checked" class="select_permission permission_module"></td>
                        <td align="center"><input value="Delete Permission" type="checkbox" checked="checked" class="select_permission permission_module"></td>
                        <td align="center"><input value="Import Permissions" type="checkbox" checked="checked" class="select_permission permission_module"></td>
                        <td align="center"><input value="Delete Permissions" type="checkbox" checked="checked" class="select_permission permission_module"></td>
                      </tr>
                    </tbody>
                  </table>



                </div>


              </div>
            </div>
          </div>
          <!--end::Card-->
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
      <form action="{{route('roles.store')}}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="">Name</label>
            <input type="text" class="form-control" value="" name="name" id="name">
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

@push('scripts')
<script>
  $(document).ready(function(e) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    Role = "{{(!empty($roles) && count($roles) > 0) ? $roles[0]->name : "
    "}}";

    $("body").on("click", ".list_items", function(e) {
      Role = $(this).attr("data-name");
      $(".active_li").removeClass("active");
      $(this).parent().addClass("active");
      $(".module_permission").prop("checked", false);
      $(".select_permission").prop("checked", false);
      getRolePermissions(Role);

    });
    $("body").on("change", ".module_permission", function(e) {
      var name = $(this).attr("id");
      type = "";
      if (Role == "") {
        $.notify("Please Select Role", 'error');
        return false;
      }

      if ($(this).is(':checked')) {
        $("." + name + "_module").prop("checked", true);
        permissions = $("." + name + "_module" + ':checkbox:checked').map(function() {
          return this.value;
        }).get();
        type = "Assign";
        console.log(permissions);


      } else {
        $("." + name + "_module").prop("checked", false);
        permissions = $("." + name + "_module" + ':checkbox:not(:checked)').map(function() {
          return this.value;
        }).get();
        type = "Revoked";
        console.log(permissions);
      }

      $.ajax({
        method: "POST",
        url: "{{route('assignRevokePermission')}}",
        data: {
          permissions: permissions,
          role: Role,
          type: type,
          module: name
        },
        success: function(response) {

          if (response.status == true) {
            $.notify("Permissions " + type + " Successfully", 'success');
          }
        }
      });

    });

    $("body").on("click", ".select_permission", function(e) {
      let value = $(this).val();
      module = value.split(' ');
      module_name = module[1].toLowerCase();
      let permissions = [];
      permissions.push(value);
      console.log(permissions);
      let type = ""
      if ($(this).is(':checked')) {
        type = "Assign";
        console.log("on " + value);
      } else {
        console.log("off " + value);
        type = "Revoked";
      }

      $.ajax({
        method: "POST",
        url: "{{route('assignRevokePermission')}}",
        data: {
          permissions: permissions,
          role: Role,
          type: type,
          module: module_name
        },
        success: function(response) {
          if (response.status == true) {
            $("#" + module_name).prop("checked", false);
            getRolePermissions(Role);
            $.notify("Permissions " + type + " Successfully", 'success');
          }
        }
      });
    });

    getRolePermissions(Role);
  });

  function getRolePermissions(role) {
    $.ajax({
      method: "POST",
      url: "{{route('getRolePermissions')}}",
      data: {
        role: role
      },
      success: function(response) {
        if (response.status == true) {
          let dpt = [];
          response.data.forEach(function(value, key) {
            $("input[type=checkbox][value='" + value.name + "']").prop("checked", true);
            dpt.push(value.module);
          });
          var unique_modules = dpt.filter(function(itm, i, a) {
            return i == a.indexOf(itm);
          });
          unique_modules.forEach(function(item) {
            $("#" + item).prop("checked", true);
          });

        }
      }
    });
  }
</script>
@endpush
