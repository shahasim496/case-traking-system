@extends('layouts.main')
@section('title','Posting Report')
@section('breadcrumb','Posting Report')


@section('content')

<div class="row affix-row global-main">

    <div class="col-sm-12 col-md-12">
        <div class="affix-content h-100">

            <form class="pt-4" method="POST" action="" enctype='multipart/form-data'>
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Cadre</label>
                            <select data-placeholder="Select Cadre" data-allow-clear="1" name="group_service_id" id="group_service_id">
                                @foreach($group_services as $gs=>$group_service)
                                <option value="{{$group_service->id}}">{{$group_service->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-3 col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="label-font">Post <span class="text-danger">*</span></label>
                            <?php $post_id = old('post_id'); ?>
                            <select data-placeholder="Select Post" data-allow-clear="1"
                            name="post_id" id="post_id">
                                <option></option>
                                @foreach($posts as $p=>$post)
                                <option value="{{$post->id}}" {{$post_id == $post->id ? 'selected' : ''}}>{{$post->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('post_id'))
                                <span class="text-danger">{{ $errors->first('post_id') }}</span>
                            @endif
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="label-font">Grade</label>
                            <select data-placeholder="Select Grade" data-allow-clear="1" name="grade_id[]" multiple="multiple" id="grade_id">
                                <option value="">All</option>
                                @foreach($grades as $gs=>$grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                    <div class="col-md-2 pt-4 text-right pr-3">
                        <button type="button" id="search" role="button" class="btn btn-primary">Search</button>
                        {{-- <a id="export_btn" href="javascript:void(0)" class="btn btn-primary">Export</a> --}}
                        <!-- <a id="excel_btn" href="javascript:void(0)" class="btn btn-primary m-2">Excel</a> -->
                    </div>

                </div>


            </form>



        </div>
    </div>
</div>

<div class="listing">
    <div class="table-responsive card">


        <table id="table_id" class="display all-user dataTable no-footer">
            <thead>
                <tr>
                    <th>P.No.</th>
                    <th>S.No</th>
                    <th>
                    Officer Name<br>
                    Domicile/DT of Birth<br>
                    Trainings<br>
                    </th>
                    <th>
                    DT of J GVT<br>
                    SER/OCC GP<br>
                    PRT Rank<br>
                    </th>

                    <th>Present Posting<br> with date of appointment</th>
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>
    </div>
</div>

@endsection

@section('jsfile')

<script>
    $(function () {

    var grade_id = '{{$grade_id}}';
    var downloadReports = '{{route('report.downloadReports')}}';
    var downloadExcelReports = '{{route('report.downloadExcelReports')}}';
    var group_service_id = '{{$group_service_id}}';
    var get_post_id = '{{$post_id}}';
    var group_service = '{{$service_name}}';
    // console.log(post_id);
    var dText = 'OFFICERS IN BS: '+grade_id+' '+group_service+' '+post_id;

    table.destroy();
    table = $('#table_id').DataTable({
        stateSave: true,
        processing: true,
        serverSide: true,
        ordering:  false,
        searching: false,
        paging: true,
        lengthMenu: [ [10,25, 50], [10,25, 50] ],
        // ajax: "{{ route('report.getReports') }}",
        ajax: {
            url: "{{ route('report.getReports') }}",
            data: function(d) {
                d.grade_id = grade_id;
                d.group_service_id = group_service_id;
                d.get_post_id = get_post_id;
            }
        },
        dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    title: function(){
                        return dText;
                    },
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        },

                        columns: [0, 1, 2, 3, 4]
                    }
                },

            ],
        "order": [],
        'columnDefs': [{ 'orderable': false, 'targets': 0 }],
        columns: [
            {data: 'p_no', name: 'p_no'},
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'rank', name: 'rank'},
            {data: 'posting', name: 'posting'},
        ]
    });

    $("#search").click(function(e) {

        grade_id = $("#grade_id").val();

        group_service_id = $("#group_service_id").val();
        get_post_id = $("#post_id").val();

        group_service = $('#group_service_id option:selected').text();
        post_id = $('#get_post_id option:selected').text();


        dText = 'OFFICERS IN BS: '+grade_id+' '+group_service+' '+post_id;

        table.draw();
        e.preventDefault();

    });//end of function

    $("#export_btn").click(function(e) {

        var params = '';
        if(group_service_id){
            params = 'group_service_id='+group_service_id+'&';
        }

        if(get_post_id){
            params += 'get_post_id='+get_post_id+'&';
        }

        if(grade_id){
            params += 'grade_id='+grade_id;
        }
        window.location.href = downloadReports+'?'+params;
    });

    $("#excel_btn").click(function(e) {

        var params = '';
        if(group_service_id){
            params = 'group_service_id='+group_service_id+'&';
        }

        if(get_post_id){
            params += 'get_post_id='+get_post_id+'&';
        }

        if(grade_id){
            params += 'grade_id='+grade_id;
        }
        window.location.href = downloadExcelReports+'?'+params;
    });

  });//end of function


</script>

@endsection
