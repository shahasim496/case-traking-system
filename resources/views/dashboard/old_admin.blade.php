@extends('layouts.admin_main')
@section('title','Classic Dashboard')
@section('breadcrumb','Classic Dashboard')

@section('content')
    <link href="{{asset('assets/css/dashboard.css')}}" rel="stylesheet" type="text/css" />

    <div class="page-content-wrapper homepage">
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
