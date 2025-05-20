@extends('layouts.main')
@section('title','Dashboard')


@section('content')

<!-- Main Content -->
<div class="main-content">

    <div class="card-custom gutter-b">

                <div class="add-role marg-20">
                    <div class="row">
                        <div class="col-lg-4">
                            <a href="ministry-add-summary.html" class="btn btn-outline mb-2" style="width: 100%; background-color: #0066cc; color: white;">Add
                                Roles</a>
                            <div class="bg-white card-custom gutter-b">
                                <ul class="user-roles">

                                    @foreach($roles as $key => $value)
                                    <?php
                                        if(empty($role_id)){
                                            $role_id = 0;
                                        }
                                    ?>
                                        <li class="{{$key == $role_id ? "active":""}} active_li">
                                            <a href="#" class="list_items" data-id="{{$value->id}}" data-name="{{$value->name}}">
                                            {{$value->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>

                        </div>
                        <div class="col-lg-8">
                            <h3 class="card-label mt-0">Module Access</h3>
                            <div class="bg-white card-custom gutter-b mb-3">

                                <ul class="module-access">
                                    <li>Establishment<span>
                                            <div class="switchToggle">
                                                <input type="checkbox" id="switch1">
                                                <label for="switch1">Toggle</label>
                                            </div>
                                        </span></li>
                                    <li>Discipline<span>
                                            <div class="switchToggle">
                                                <input type="checkbox" id="switch2">
                                                <label for="switch2">Toggle</label>
                                            </div>
                                        </span></li>
                                    <li>Training<span>
                                            <div class="switchToggle">
                                                <input type="checkbox" id="switch3">
                                                <label for="switch3">Toggle</label>
                                            </div>
                                        </span></li>



                                </ul>


                            </div>


                            <table width="100%" border="0" class="bg-white display table roles-right">
                                <thead>
                                    <tr>
                                        <th>Module Permission</th>
                                        <th>Read</th>
                                        <th>Write</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Establishment</td>
                                        <td align="center"><input type="checkbox" checked="checked" id="1" class="vh"><label for="1"></label></td>
                                        <td align="center"><input type="checkbox" checked="checked" id="6" class="vh"><label for="6"></label></td>
                                    </tr>
                                    <tr>
                                        <td>Discipline</td>
                                        <td align="center"><input type="checkbox" checked="checked" id="9" class="vh"><label for="9"></label></td>
                                        <td align="center"><input type="checkbox" checked="checked" id="10" class="vh"><label for="10"></label></td>
                                    </tr>
                                    <tr>
                                        <td>Training</td>
                                        <td align="center"><input type="checkbox" checked="checked" id="15" class="vh"><label for="15"></label></td>
                                        <td align="center"><input type="checkbox" checked="checked" id="16" class="vh"><label for="16"></label></td>

                                    </tr>

                                </tbody>
                            </table>



                        </div>


                    </div>
                </div>
            </div>

</div>


@endsection

@section('jsfile')

<script>

</script>

@endsection
