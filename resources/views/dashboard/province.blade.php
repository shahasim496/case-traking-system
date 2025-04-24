@extends('layouts.admin_main')
@section('title','Province Cadre Strenght')
@section('breadcrumb','Province Cadre Strenght')

@section('content')

<div class="page-content form-main-2">
    <h5>Province wise Cadre Strenght</h5>
    <div class=" table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">Cadre</th>
                @foreach($provinces as $p=>$province)
                <th colspan="6">{{$province->name}}</th>
                @endforeach

            </tr>
            <tr>
                @foreach($provinces as $p=>$province)
                <th>17</th>
                <th>18</th>
                <th>19</th>
                <th>20</th>
                <th>21</th>
                <th>22</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($reports as $r=>$report)
            <tr>
                <td style="width: 20%;"><a href="{{route('report.officers',['group_service_id'=>$report['id']])}}">{{$report['name']}}</a></td>
                @foreach($report['data'] as $d=>$pro_data)

                <td>{{$pro_data->seventeen}}</td>
                <td>{{$pro_data->eighteen}}</td>
                <td>{{$pro_data->nineteen}}</td>
                <td>{{$pro_data->twenty}}</td>
                <td>{{$pro_data->twenty_one}}</td>
                <td>{{$pro_data->twenty_two}}</td>
                @endforeach
            </tr>
            @endforeach

            <tr class="reporting_row">
            <td style="width: 20%;">Total</td>
            @foreach($province_total as $pt=>$p_total)
            <td>{{$p_total->seventeen}}</td>
            <td>{{$p_total->eighteen}}</td>
            <td>{{$p_total->nineteen}}</td>
            <td>{{$p_total->twenty}}</td>
            <td>{{$p_total->twenty_one}}</td>
            <td>{{$p_total->twenty_two}}</td>
            @endforeach

        </tr>

        </tbody>
    </table>
    </div>
    </div>

@endsection

@section('jsfile')


@endsection
