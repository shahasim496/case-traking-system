@extends('layouts.admin_main')
@section('title','Province Cadre Strength')
@section('breadcrumb','Province Cadre Strength')

@section('content')

<div class="page-content form-main-2">
    <h5>Province wise Cadre Strength</h5>
    <div class=" table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">Cadre</th>
                <th colspan="6">{{$province->name}}</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>

                <th>17</th>
                <th>18</th>
                <th>19</th>
                <th>20</th>
                <th>21</th>
                <th>22</th>
            </tr>

        </thead>
        <tbody class="bg-white">

            <tr>
                <td>
                {{$cadre->name}}
                </td>
                <td><a href="{{route('report.officers',['group_service_id'=>$cadre->id,'province_id'=>$province->id,'grade_id'=>17])}}">{{$report->seventeen}}</a></td>
                <td><a href="{{route('report.officers',['group_service_id'=>$cadre->id,'province_id'=>$province->id,'grade_id'=>18])}}">{{$report->eighteen}}</a></td>
                <td><a href="{{route('report.officers',['group_service_id'=>$cadre->id,'province_id'=>$province->id,'grade_id'=>19])}}">{{$report->nineteen}}</a></td>
                <td><a href="{{route('report.officers',['group_service_id'=>$cadre->id,'province_id'=>$province->id,'grade_id'=>20])}}">{{$report->twenty}}</a></td>
                <td><a href="{{route('report.officers',['group_service_id'=>$cadre->id,'province_id'=>$province->id,'grade_id'=>21])}}">{{$report->twenty_one}}</a></td>
                <td><a href="{{route('report.officers',['group_service_id'=>$cadre->id,'province_id'=>$province->id,'grade_id'=>22])}}">{{$report->twenty_two}}</a></td>
                <td>{{$report->group_wise_count}}</td>
            </tr>

        </tbody>
    </table>
    <a href="{{route('report.province')}}" class="btn btn-primary">Back</a>
    </div>
    </div>

@endsection

@section('jsfile')


@endsection
