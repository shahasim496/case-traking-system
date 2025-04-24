@extends('layouts.admin_main')
@section('title','Classic Dashboard')
@section('breadcrumb','Classic Dashboard')

@section('content')

<div class="page-content  dashboard">
    <h5>Classic Dashboard</h5>
    <div class="card ">
        <div class="card-head">
            <h5>Officers</h5>
        </div>
        <div class="row mt-3 pl-2 pr-2">

            @foreach($data['group_services'] as $gs=>$group_service)
            <div class="col-lg-6 col-12 col-md-6 mb-3">
                <div class="block">
                    <div class="row  pl-2 pr-2 pt-2">
                        <div class="col">
                            <h6><a href="{{route('report.officers',['group_service_id'=>$group_service->id])}}">{{$group_service->name}}</a></h6>
                        </div>
                        <div class="col text-right">
                            <a type="button" href="{{route('report.officers',['group_service_id'=>$group_service->id])}}" role="button" class="btn btn-detail">{{$group_service->officers->count()}}</a>
                        </div>
                    </div>
                    <div class="  mt-3 pb-2 text-center">
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

    <!-- <div class="card mt-5">
        <div class="card-head">
            <h5>Cadre strenght/List of Post</h5>
        </div>
        <div class="row mt-3 pl-2 pr-2">
            <div class="col-lg-6 col-12 col-md-6 mb-3">
                <div class="block">
                    <div class="row  pl-2 pr-2 pt-2">
                        <div class="col">
                            <h6>Total Cadre Strenght</h6>
                        </div>
                        <div class="col text-right">
                            <button type="button" role="button" class="btn btn-detail">66</button>
                        </div>
                    </div>
                    <div class="text-center  mt-3 pb-2">
                        <button class="btn btn-officer-2">BS 22 <span>(1)</span></button>
                        <button class="btn btn-officer-2">BS 21 <span>(0)</span></button>
                        <button class="btn btn-officer-2">BS 20 <span>(1)</span></button>
                        <button class="btn btn-officer-2">BS 19 <span>(1)</span></button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12 col-md-6 mb-3">
                <div class="block">
                    <div class="row  pl-2 pr-2 pt-2">
                        <div class="col">
                            <h6>Vacant Slot</h6>
                        </div>
                        <div class="col text-right">
                            <button type="button" role="button" class="btn btn-detail">55</button>
                        </div>
                    </div>
                    <div class="text-center mt-3 pb-2">
                        <button class="btn btn-officer-2">BS 22 <span>(1)</span></button>
                        <button class="btn btn-officer-2">BS 21 <span>(1)</span></button>
                        <button class="btn btn-officer-2">BS 20 <span>(1)</span></button>
                        <button class="btn btn-officer-2">BS 19 <span>(1)</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-head">
            <div class="row  pl-2 pr-2 pt-1">
                <div class="col">
                    <h6>Accounts</h6>
                </div>
                <div class="col text-right pt-0 ">
                    <button type="button" role="button" class="btn btn-officer-main">55</button>
                </div>
            </div>
        </div>
        <div class=" mt-3 pl-4 pr-2 pb-3 ">
            <a href="">View all Accounts</a>
        </div>
    </div> -->

    </div>

@endsection

@section('jsfile')


@endsection
