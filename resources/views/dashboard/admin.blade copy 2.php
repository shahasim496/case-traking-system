@extends('layouts.admin_main')
@section('title','Dashboard')
@section('breadcrumb','Dashboard')

@section('content')
    <link href="{{asset('assets/css/dashboard.css')}}" rel="stylesheet" type="text/css" />
    <div class="page-content-wrapper homepage">
        <div class="page-content form-main-2">
            <!-- new welcome -->

            <div class="row pl-2">
                <div class="col-lg-4 col-md-4 col-12 mb-1">
                    <h4 class="mt-1"> Welcome to, <span class="color-theme bold"></span> </h4>
                </div>
                <div class="col ">
                    <div class="row pull-right pr-4 cadre">
                        <h3 class="mt-1 ml-4">Total Cadre: <span class="color-theme bold ml-1">{{$data['total_cadre_views']->cadre}}</span></h3>
                        <h3 class="mt-1 ml-4">Ex-Cadre:<span class="color-theme bold ml-1">{{$data['total_cadre_views']->ex_cadre}}</span></h3>
                        <h3 class="mt-1 ml-4">Non-Cadre: <span class="color-theme bold ml-1">{{$data['total_cadre_views']->non_cadre}}</span></h3>
                    </div>
                </div>
            </div>

            <!-- new welcome -->

            <ul class="nav nav-pills dashboard-tab mt-1" id="pills-tab" role="tablist">

                @foreach($data['group_services'] as $gs=>$group_service)
                <li class="nav-item ">
                    <a style="text-align:left; background: {{$group_service->color??'#fff'}}" class="nav-link  {{$loop->first?'active':''}}" id="pills-tab{{$group_service->id}}-tab" data-toggle="pill" href="#pills-tab{{$group_service->id}}" role="tab" aria-controls="pills-tab1"  onclick="smoothScroll(document.getElementById('pills-tabContent'))" aria-selected="true">

                    <div class="row">
                        <div class="col-3 ">
                            <img src="{{asset('/images/icons/'.$group_service->image)}}" alt="">
                        </div>
                        <div class="col-9 text-right pt-2">
                            <h3 class="mb-2 mr-3">{{$data['group_total'][$gs]->group_wise_count}}</h3>

                        </div>

                    </div>
                    <p class="mt-2">{{$group_service->name}}</p>

                    </a>
                </li>
                @endforeach

            </ul>
            <div class="tab-content card pt-4 pl-3  pb-3 pr-3" id="pills-tabContent">
                @foreach($data['group_services'] as $gs=>$group_service)

                <div class="tab-pane fade {{$loop->first?' show active':''}}" id="pills-tab{{$group_service->id}}" role="tabpanel" aria-labelledby="pills-tab{{$group_service->id}}-tab">
                    <div class="block pl-3">

                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-12 text-center">
                            <img src="/assets/img/big_icons/{{$group_service->id}}.png" alt="">
                        </div>
                        <div class="col-lg-8 col-md-7 col-12 pl-0">
                            <h5 style="font-size: 22px; font-weight: 600;">{{$group_service->name}}</h5>

                        <div class=" mt-3 pb-2">
                            <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>22])}}" class="btn btn-officer-2">BS 22 <span>({{$data['group_total'][$gs]->twenty_two}})</span></a>
                            <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>21])}}" class="btn btn-officer-2">BS 21 <span>({{$data['group_total'][$gs]->twenty_one}})</span></a>
                            <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>20])}}" class="btn btn-officer-2">BS 20 <span>({{$data['group_total'][$gs]->twenty}})</span></a>
                            <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>19])}}" class="btn btn-officer-2">BS 19 <span>({{$data['group_total'][$gs]->nineteen}})</span></a>
                            <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>18])}}" class="btn btn-officer-2">BS 18 <span>({{$data['group_total'][$gs]->eighteen}})</span></a>
                            <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>17])}}" class="btn btn-officer-2">BS 17 <span>({{$data['group_total'][$gs]->seventeen}})</span></a>

                        </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-12">
                            <h5 style="font-size: 22px; font-weight: 600;">Total</h5>
                            <button class="btn btn-officer-2 ml-3 mt-0">{{$data['group_total'][$gs]->group_wise_count}}</button>

                        </div>
                    </div>

                    </div>
                </div>
                @endforeach
                 </div>
            <!-- Ended -->








        </div>
@if(false)
        <div class="page-content switching  tabs tabs-custom">
            <div class="row mt-3">

                @foreach($data['group_services'] as $gs=>$group_service)

                    <div class="col-lg-3 col-md-3 col-12 mb-3" >
                        <a class="tab-btn " href="#tab{{$group_service->id}}" >
                        <div class="tabs-stats bg1" style="background: {{$group_service->color}}" data-switch="#tab{{$group_service->id}}" onclick="smoothScroll(document.getElementById('page-2'))">
                            <div class="media">
                                <div class="media-body">
                                    <span >{{$group_service->activeOfficers->count()}}</span>
                                    <h5 class="mb-0 mt-1">{{$group_service->name}}</h5>
                                </div>
                            </div>

                        </div>
                        </a>
                    </div>

                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link block-1 {{$gs == 0 ? 'active' : '' }}" id="pills-tab1-tab" data-toggle="pill"--}}
                    {{--                           href="#pills-tab{{$group_service->id}}" role="tab" aria-controls="pills-tab{{$group_service->id}}" aria-selected="true">--}}
                    {{--                            {{$group_service->name}} <br>--}}
                    {{--                            <span >{{$group_service->officers->count()}}</span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                @endforeach



            </div>
            <div class="card">
                <div id="page-2" >
                    @foreach($data['group_services'] as $gs=>$group_service)

                        <div id="tab{{$group_service->id}}" @if(!$loop->first) style="display: none;" @endif>
                            <div class="col-12 block-main  p-3">
                                <div class="row  pl-2 pr-2 ">
                                    <div class="col">
                                        <h6>{{$group_service->name}}</h6>
                                    </div>
                                    <div class="col text-right">
                                        <button type="button" role="button" class="btn btn-detail">{{$group_service->activeOfficers->count()}}</button>
                                    </div>
                                </div>
                                <div class="text-center mt-3 pb-2">
                                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>22])}}" class="btn btn-officer-2">BS 22 <span>({{$data['group_total'][$gs]->twenty_two}})</span></a>
                                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>21])}}" class="btn btn-officer-2">BS 21 <span>({{$data['group_total'][$gs]->twenty_one}})</span></a>
                                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>20])}}" class="btn btn-officer-2">BS 20 <span>({{$data['group_total'][$gs]->twenty}})</span></a>
                                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>19])}}" class="btn btn-officer-2">BS 19 <span>({{$data['group_total'][$gs]->nineteen}})</span></a>
                                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>18])}}" class="btn btn-officer-2">BS 18 <span>({{$data['group_total'][$gs]->eighteen}})</span></a>
                                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id,'grade_id'=>17])}}" class="btn btn-officer-2">BS 17 <span>({{$data['group_total'][$gs]->seventeen}})</span></a>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
@endif


    </div>
    </div>

@endsection

@section('jsfile')

    <script>
        $('[data-switch]').on('click', function (e) {
            var $page = $('#page-2'),
                blockToShow = e.currentTarget.getAttribute('data-switch');
            // Hide all children.
            $page.children().hide();
            // And show the requested component.
            $page.children(blockToShow).show();
        });
    </script>



    <script>
        window.smoothScroll = function(target) {
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

            scroll = function(c, a, b, i) {
                i++; if (i > 30) return;
                c.scrollTop = a + (b - a) / 30 * i;
                setTimeout(function(){ scroll(c, a, b, i); }, 20);
            }
            // start scrolling
            scroll(scrollContainer, scrollContainer.scrollTop, targetY, 0);
        }
    </script>
@endsection
