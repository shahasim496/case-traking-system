<!-- start header -->
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner">
            <!-- logo start -->
            <div class="page-logo sidemenu-open">
                <a href="{{route('home')}}">
            <img src="{{asset('assets/img/')}}" alt="" class="mt-0">
                </a>
            </div>
            <div class="page-logo sidemenu-closed">
                <a href="{{route('home')}}">
            <img src="{{asset('assets/img/')}}" alt="" class="mt-0">
                </a>
            </div>

            <ul class="nav navbar-nav navbar-left in">
                <li><a href="#" class="menu-toggler sidebar-toggler  img-text"><img src="{{asset('/assets/img/bar.png')}}" alt=""></a></li>
            </ul>
            <h4 class="nitb-tittle">@yield('breadcrumb')</h4>
            <!-- start mobile menu -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                data-target=".navbar-collapse">
                <span></span>
            </a>
            <!-- end mobile menu -->
            <!-- start header menu -->
            <div class="top-menu pt-1">

                <ul class="nav navbar-nav pull-right">
                    <!-- start manage user dropdown -->
        
                    <!-- start notification dropdown -->
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="javascript:;" class="dropdown-toggle p-0  img-text" data-toggle="dropdown" data-hover="dropdown"
                            data-close-others="true">
                            <!-- <i class="fa fa-bell-o"></i> -->
                            <!-- <img src="{{asset('/assets/img/Bell.png')}}" alt=""> -->
                            <!-- <span class="badge headerBadgeColor1"> {{Auth::user()->unreadNotifications->count()}} </span> -->
                        </a>
                        <ul class="dropdown-menu animated swing">
                            <li class="external">
                                <h3><span class="bold">Notifications</span></h3>
                                <span class="notification-label purple-bgcolor">New {{Auth::user()->unreadNotifications->count()}}</span>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                                @if(count($user_notifications) > 0)
                        @foreach($user_notifications as $un=>$noti)
                        <li>
                        <a href="javascript:;">
                            <span class="notification-icon circle blue-bgcolor"><img alt="" src="{{asset('assets/img/ic_bell.png')}}"></span>
                            <span class="details">
                                <b>{{$noti->data['title']}}</b>
                                <p>{{$noti->data['description']}}</p>
                                <span class="time">{{date("jS F, Y",strtotime($noti->data['notification_date']))}}</span>
                            </span>
                        </a>
                        </li>
                        @endforeach

                        @else

                        <li>
                        <a href="javascript:;">
                            <span class="notification-icon circle blue-bgcolor"><img alt="" src="{{asset('assets/img/ic_bell.png')}}"></span>
                            <span class="details">
                                <b></b>
                                <p>No notification found.<p>
                            </span>
                        </a>
                        </li>

                        @endif

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- end notification dropdown -->
                    <!-- start manage user dropdown -->
                    <li class="dropdown dropdown-user pt-0" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                        data-close-others="true">
                        <img alt="" class="img-circle img-2" src="{{Auth::user()->profile ? Auth::user()->profile->picture_attachment_id : '/images/profile.jpg'}}" />
                        <span class="username username-hide-on-mobile img-text"> <b>{{Auth::user()->name}} </b>  <br>Police Officer<i class="fa fa-angle-down" aria-hidden="true"></i> </span>


                        </a>
                        <ul class="dropdown-menu dropdown-menu-default animated jello">
                        <li>
                            <a href="#">
                            <i class="fa fa-cog" aria-hidden="true"></i> Settings </a>
                        </li>
                            <li>

                            <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off" aria-hidden="true"></i> Log Out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                        </li>
                    </ul>
                </li>
                    <li>
                        <img class="mt-1 display-none" src="{{asset('/assets/img/')}}" alt="">
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end header -->

