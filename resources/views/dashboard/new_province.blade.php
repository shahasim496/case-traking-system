@extends('layouts.admin_main')
@section('title','Federal/Province/Region Cadre Strength')
@section('breadcrumb','Federal/Province/Region Cadre Strength')

@section('content')

<div class="page-content form-main-2">
    <h5>Federal/Province/Region wise Cadre Strength</h5>
    <div class=" table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th rowspan="2">Cadre</th>
                @foreach($provinces as $p=>$province)
                <th rowspan="2">{{$province->name}}</th>
                @endforeach
                <th rowspan="2">Total</th>
            </tr>

        </thead>

        @php
            $feTtoal = 0;
            $iTtoal = 0;
            $faTtoal = 0;
            $giTtoal = 0;
            $ajTtoal = 0;
            $baTtoal = 0;
            $kpTtoal = 0;
            $suTtoal = 0;
            $srTtoal = 0;
            $puTtoal = 0;
            $gTtoal = 0;
            $cTotal = array();
        @endphp

        <tbody class="bg-white">
            @foreach($reports as $r=>$report)
            <tr>
                @php
                    $sTtoal = 0;
                @endphp
                <td style="width: 20%;">
                <a href="{{route('report.officers',['group_service_id'=>$report['id']])}}">{{$report['name']}}</a>
                </td>
                @foreach($report['data'] as $d=>$pro_data)

                @php
                    $sTtoal += $pro_data->group_wise_count;
                @endphp
                <td>
                    @if($pro_data->group_wise_count > 0)
                    <a href="{{route('report.provinceWiseCadre',['group_service_id'=>$report['id'],'province_id'=>$pro_data->province_id])}}">{{$pro_data->group_wise_count}}</a>
                    @else
                    {{$pro_data->group_wise_count}}
                    @endif
                </td>
                @endforeach

                @php
                    $gTtoal += $sTtoal;
                @endphp

                <td>{{$sTtoal}}</td>
            </tr>
            @endforeach

            <tr class="reporting_row">
            <td style="width: 20%;">Total</td>
            @foreach($province_total as $pt=>$pTotal)
                <td>{{$pTotal->total_cadre}}</td>
            @endforeach
            <td>{{$gTtoal}}</td>

        </tr>

        </tbody>
    </table>
    </div>
    </div>

@endsection

@section('jsfile')


@endsection
