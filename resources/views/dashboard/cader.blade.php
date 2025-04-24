@extends('layouts.admin_main')
@section('title','Cadre Strength')
@section('breadcrumb','Cadre Strength')

@section('content')

<div class="page-content form-main-2">
    <h5>Total Cadre Strength</h5>
    <div class="  table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th rowspan="2">Cadre</th>
                <th colspan="{{count($data['grades'])}}">Grade</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                @foreach($data['grades'] as $g=>$grade)
                <th>{{$grade->name}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white">
            @php
            $total = 0;
            $subTotal = 0;
            $totalArray = [];
            @endphp

            @foreach($data['group_services'] as $gs=>$group_service)

            @php
                $total+=$group_service->group_grade_count;
            @endphp
            <tr>
                <td style="width: 20%;">
                    <a href="{{route('report.officers',['group_service_id'=>$group_service->id])}}"> {{$group_service->name}} </a>
                </td>

                @foreach($data['grades'] as $g=>$grade)

                @php
                    $grade->group_id = $group_service->id;
                    $totalArray[$g] = isset($totalArray[$g]) ? $totalArray[$g]+=$grade->group_count : $grade->group_count;
                @endphp
                <th>{{$grade->group_count}}</th>
                @endforeach

                <td>{{$group_service->group_grade_count}}</td>

            </tr>
            @endforeach

        <tr class="reporting_row">
            <td style="width: 20%;">Total</td>
            @foreach($totalArray as $t=>$tCount)
                <th>{{$tCount}}</th>
            @endforeach
            <td>{{$total}}</td>
        </tr>


        </tbody>
    </table>
</div>
</div>

@endsection

@section('jsfile')


@endsection
