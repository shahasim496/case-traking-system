<!-- start sidebar menu -->
<div class="sidebar-container">
    <div class="sidemenu-container navbar-collapse collapse fixed-menu " style="position:
     fixed; top: 0; left: 0; height: 100%; overflow-y: auto; background-color: #fff; z-index: 1000;">
        <div id="remove-scroll">
            <ul class="sidemenu page-header-fixed  p-t-20" data-keep-expanded="false" data-auto-scroll="true"
                data-slide-speed="200">

            

                <li class="nav-item text-center mb-4">
                <img src="/assets/img/login/Ministry-Logo.png" alt="Logo" class="sidebar-logo" style="margin-top: 40px; width: 120px; height: auto; max-width: 100%;">
                </li>


                <li class="nav-item">
                    <a href="{{ url('/home') }}" class="nav-link">
                        <i class="fa fa-tachometer " style="font-size: 18px; color: gray;"></i>
                        <span class="title">Dashboard</span>
                        <span class="selected"></span>
                    </a>
                </li>



                @if(auth()->user()->can('manage users'))
<!-- manage user -->

                <li class="nav-item">
                    <a href="{{route('users')}}" class="nav-link nav-toggle">
                        <i class="fa fa-user " style="font-size: 18px; color: gray;"></i>
                        <span class="title">User Management</span>
                        <span class="arrow"></span>

                    </a>
                   
                @endif
                
                @if(auth()->user()->can('manage role and permissions'))
<!-- manage role and permissions -->
                <li class="nav-item">
                    <a class="nav-link nav-toggle">
                        <i class="fa fa-user-circle-o " style="font-size: 18px; color: gray;"></i>
                        <span class="title">Role & Permission</span>
                        <span class="arrow"></span>

                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('roles') }}" class="nav-link">
                                <span class="title">Manage Roles</span>
                            </a>
                        </li>
                      
                       

                        <li class="nav-item">
                            <a href="{{ route('roles.managePermissions') }}" class="nav-link">
                                <span class="title">Manage Permissions Assignment</span>
                            </a>
                        </li>
                    </ul>
                    @endif


                    @if(auth()->user()->can('manage settings'))
<!-- manage settings -->
                <li class="nav-item">
                    <a class="nav-link nav-toggle">
                        <i class="fa fa-wrench" style="font-size: 18px; color: gray;"></i>
                        <span class="title">Settings</span>
                        <span class="arrow"></span>

                    </a>

                  
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('departments') }}" class="nav-link">
                                <span class="title">Manage Departments</span>
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a href="{{ route('designations') }}" class="nav-link">
                                <span class="title">Manage Designations</span>

                            </a>
                        </li>
                       


                    </ul>

                </li>

                @endif


              





<!-- change password -->
                <li class="nav-item">
                    <a href="{{ route('user.changePassword') }}" class="nav-link">
                        <i class="fa fa-key" style="font-size: 18px; color: gray;"></i>
                        <span class="title">Change Password</span>
                        <span class="selected"></span>
                    </a>
                </li>

             



                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" style="font-size: 18px; color: gray;"></i>
                        <span class="title">Log Out</span>

                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- end sidebar menu -->