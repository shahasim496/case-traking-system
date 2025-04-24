@extends('layouts.admin_main')
@section('title','OSD/Awaited Cadre Strength')
@section('breadcrumb','OSD/Awaited Cadre Strength')

@section('content')

<div class="page-content form-main-2">
    <h5>OSD/Awaited wise Cadre Strength</h5>
    <div class=" table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th >Cadre</th>
                <th >OSD</th>
                <th >Awaiting Post</th>
                <th >Total</th>
            </tr>

        </thead>

        @php
            $gTtoal =  0;
            $OgTtoal =  0;
            $AgTtoal =  0;
        @endphp

        <tbody class="bg-white">
            @foreach($reports as $r=>$report)
            <tr>
                @php
                    $sTtoal = 0;
                    $sTtoal = $report['data']->awaiting_posted+$report['data']->osd;
                    $gTtoal+=$sTtoal;
                    $OgTtoal+=$report['data']->osd;
                    $AgTtoal+=$report['data']->awaiting_posted;
                @endphp
                <td style="width: 40%;">
                <a href="{{route('report.officers',['group_service_id'=>$report['id']])}}">{{$report['name']}}</a>
                </td>

                <td >{{$report['data']->osd}}</td>
                <td >{{$report['data']->awaiting_posted}}</td>
                <td>{{$sTtoal}}</td>
            </tr>
            @endforeach

            <tr class="reporting_row">
            <td style="width: 20%;">Total</td>

            <td>{{$OgTtoal}}</td>
            <td>{{$AgTtoal}}</td>
            <td>{{$gTtoal}}</td>

        </tr>

        </tbody>
    </table>
    </div>
    </div>

@endsection

@section('jsfile')


@endsection
