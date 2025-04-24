@extends('layouts.main')
@section('title','Dashboard')
@section('breadcrumb','Welcome to NOC,')
@section('content')

<div class="row affix-row">
    @include('layouts.inner_sidebar',['inner_side'=>3])
    <div class="col-sm-12 col-md-8">
        <div class="affix-content">
            <div class="page-header">
                <h3>Travel Abroad</h3>
            </div>

            <form method="POST" action="{{ route('travel.store') }}" enctype='multipart/form-data'>
            @csrf

            <input type="hidden" id="is_part_time" name="is_part_time" value="1">
            <input type="hidden" id="subcategory_id" name="subcategory_id" value="4">

                <div class="row tab-contents">
                    <div class="col-lg-12 issue-deatil">
                                <h5>Particular</h5>
                    </div>
                    <div class="col-lg-12 mb-3">
                    <label class="label-font">Present Place of Posting</label>
                    <input type="text" id="posting_place" name="posting_place"
                    class="form-control " placeholder="Enter present place of posting"
                    required="" value="{{old('posting_place')}}">

                    @if ($errors->has('posting_place'))
                        <span class="text-danger">{{ $errors->first('posting_place') }}</span>
                    @endif

                    </div>

                    <div class="col-lg-12 mb-3">
                    <label class="label-font">Passport No.</label>
                    <input type="text" id="passport_number" name="passport_number" class="form-control "
                    placeholder="Enter passport no." required="" value="{{old('passport_number')}}">

                    @if ($errors->has('passport_number'))
                        <span class="text-danger">{{ $errors->first('passport_number') }}</span>
                    @endif

                    </div>

                    <div class="col-lg-12 mb-3">
                    <label class="label-font">Passport Issuance Date</label>
                    <input type="Date" id="issuance_date" name="issuance_date"
                    class="form-control " required="" value="{{old('issuance_date')}}">

                    @if ($errors->has('issuance_date'))
                        <span class="text-danger">{{ $errors->first('issuance_date') }}</span>
                    @endif
                    </div>

                    <div class="col-lg-12 mb-3">
                    <label class="label-font">Passport Expiry Date</label>
                    <input type="Date" id="expiry_date" name="expiry_date"
                    class="form-control " required="" value="{{old('expiry_date')}}">

                    @if ($errors->has('expiry_date'))
                        <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                    @endif

                    </div>

                    <div class="col-lg-12 custom-file-upload mb-3">
                    <label class="label-font">Attach scanned copy of 1st three pages</label>
                    <input type="file" id="passport_attachment"
                    name="passport_attachment"/>

                    @if ($errors->has('passport_attachment'))
                        <span class="text-danger">{{ $errors->first('passport_attachment') }}</span>
                    @endif
                    </div>

                    <div class="col-lg-12 issue-deatil">
                                <h5>Details</h5>
                    </div>


                                <div class="col-lg-12 mb-1">
                                <label class="label-font mb-2">Nature of Visit</label>
                                <div class="row radio-btn">
                                    <div class="col-md-4">
                                        <div class="float-end">
                                            <input type="radio" id="Official/Gratis" name="is_official" value="1" checked>
                                            <label for="Official/Gratis">Official</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="radio" id="Ordinary" name="is_official" value="0">
                                        <label for="Ordinary">Personal</label>
                                    </div>
                                </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                    <label class="label-font">Nature of Leave</label>
                    <input type="text" id="leave_nature" name="leave_nature" value="{{old('leave_nature')}}"
                    class="form-control " placeholder="Write  nature of leave details visit abroad..." required="">

                    @if ($errors->has('leave_nature'))
                        <span class="text-danger">{{ $errors->first('leave_nature') }}</span>
                    @endif
                    </div>


                            <div class="col-lg-6 mb-3">
                                    <label class="label-font">Duration From</label>
                                    <input type="Date" id="duration_from" name="duration_from"
                                    class="form-control" value="{{old('duration_from')}}" placeholder="From">

                                    @if ($errors->has('duration_from'))
                                    <span class="text-danger">{{ $errors->first('duration_from') }}</span>
                                    @endif
                            </div>
                            <div class="col-lg-6 mb-3">

                                    <label class="label-font">Duration To</label>
                                    <input type="Date" id="duration_to" name="duration_to"
                                    class="form-control" value="{{old('duration_to')}}" placeholder="To">

                                    @if ($errors->has('duration_to'))
                                    <span class="text-danger">{{ $errors->first('duration_to') }}</span>
                                    @endif
                            </div>


                                <div class="col-lg-12 mb-1">
                                <label class="label-font">Nominating Authority</label>
                                <div class="row radio-btn">
                                    <div class="col-md-12">
                                        <div class="float-end">
                                            <input type="radio" id="Yes" name="is_nominating_authority" value="1">
                                            <label for="Yes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" id="No" name="is_nominating_authority" value="0">
                                        <label for="No">No</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" id="Na" name="is_nominating_authority" value="" checked>
                                        <label for="Na">Not Applicable</label>
                                    </div>
                                </div>
                                </div>
                                <div class="desc mb-3" id="twoCarDiv" style="width: 100%; display: none;">
                                <div class="col-lg-12 mb-3">
                                    <label class="label-font">Name</label>
                                    <input type="text" id="authority_name" name="authority_name" class="form-control mb-3"
                                    placeholder="Enter name of nominating Authority" >

                                    @if ($errors->has('authority_name'))
                                        <span class="text-danger">{{ $errors->first('authority_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-12 custom-file-upload mb-3">
                    <label class="label-font">Attach approval</label>
                    <input type="file" id="nominating_attachment" name="nominating_attachment"/>
                    </div>

                    @if ($errors->has('nominating_attachment'))
                        <span class="text-danger">{{ $errors->first('nominating_attachment') }}</span>
                    @endif

                                </div>
                                <div class="col-lg-12 mb-1">
                                <label class="label-font">Sponsoring Agency</label>
                                <div class="row radio-btn">
                                    <div class="col-md-12">
                                        <div class="float-end">
                                            <input type="radio" id="Yes1" name="is_sponsoring_agency" value="1">
                                            <label for="Yes1">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" id="No1" name="is_sponsoring_agency" value="0">
                                        <label for="No1">No</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="radio" id="Na1" name="is_sponsoring_agency" value="" checked>
                                        <label for="Na1">Not Applicable</label>
                                    </div>
                                </div>
                                </div>
                                <div class="desc2 mb-3" id="twoCarDiv-two" style="width: 100%; display: none;">
                                <div class="col-lg-12 mb-3">
                                    <label class="label-font">Name</label>
                                    <input type="text" id="agency_name" name="agency_name" class="form-control "
                                    placeholder="Enter name of nominating Authority" >

                                    @if ($errors->has('agency_name'))
                                        <span class="text-danger">{{ $errors->first('agency_name') }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-12 custom-file-upload mb-3">
                    <label class="label-font">Attach approval</label>
                    <input type="file" id="sponsoring_attachment" name="sponsoring_attachment"/>
                    </div>

                    @if ($errors->has('sponsoring_attachment'))
                        <span class="text-danger">{{ $errors->first('sponsoring_attachment') }}</span>
                    @endif

                                </div>


                                <div class="col-lg-12 btn-submit mt-2 mb-2">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </div>


                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('jsfile')

<script>
    $(function() {
    var $tabButtonItem = $('#tab-button li'),
    $tabSelect = $('#tab-select'),
    $tabContents = $('.tab-contents'),
    activeClass = 'is-active';

    $tabButtonItem.first().addClass(activeClass);
    $tabContents.not(':first').hide();

    $tabButtonItem.find('a').on('click', function(e) {
    var target = $(this).attr('href');

    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);
    $tabSelect.val(target);
    $tabContents.hide();
    $(target).show();
    e.preventDefault();
    });

    $tabSelect.on('change', function() {
    var target = $(this).val(),
    targetSelectNum = $(this).prop('selectedIndex');

    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.hide();
    $(target).show();
    });
    });
</script>
<script type="text/javascript">
    //Reference:
    //https://www.onextrapixel.com/2012/12/10/how-to-create-a-custom-file-input-with-jquery-css3-and-php/
    ;(function($) {

    // Browser supports HTML5 multiple file?
    var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
    isIE = /msie/i.test( navigator.userAgent );

    $.fn.customFile = function() {

    return this.each(function() {

    var $file = $(this).addClass('custom-file-upload-hidden'), // the original file input
        $wrap = $('<div class="file-upload-wrapper">'),
        $input = $('<input type="text" class="file-upload-input" />'),
        // Button that will be used in non-IE browsers
        $button = $('<button type="button" class="file-upload-button"><i class="fa fa-paperclip pr-2" aria-hidden="true"></i>Browse</button>'),
        // Hack for IE
        $label = $('<label class="file-upload-button" for="'+ $file[0].id +'"><i class="fa fa-paperclip" aria-hidden="true"></i>Select a File</label>');

    // Hide by shifting to the left so we
    // can still trigger events
    $file.css({
        position: 'absolute',
        left: '-9999px'
    });

    $wrap.insertAfter( $file )
        .append( $file, $input, ( isIE ? $label : $button ) );

    // Prevent focus
    $file.attr('tabIndex', -1);
    $button.attr('tabIndex', -1);

    $button.click(function () {
        $file.focus().click(); // Open dialog
    });

    $file.change(function() {

        var files = [], fileArr, filename;

        // If multiple is supported then extract
        // all filenames from the file array
        if ( multipleSupport ) {
        fileArr = $file[0].files;
        for ( var i = 0, len = fileArr.length; i < len; i++ ) {
            files.push( fileArr[i].name );
        }
        filename = files.join(', ');

        // If not supported then just take the value
        // and remove the path to just show the filename
        } else {
        filename = $file.val().split('\\').pop();
        }

        $input.val( filename ) // Set the value
        .attr('title', filename) // Show filename in title tootlip
        .focus(); // Regain focus

    });

    $input.on({
        blur: function() { $file.trigger('blur'); },
        keydown: function( e ) {
        if ( e.which === 13 ) { // Enter
            if ( !isIE ) { $file.trigger('click'); }
        } else if ( e.which === 8 || e.which === 46 ) { // Backspace & Del
            // On some browsers the value is read-only
            // with this trick we remove the old input and add
            // a clean clone with all the original events attached
            $file.replaceWith( $file = $file.clone( true ) );
            $file.trigger('change');
            $input.val('');
        } else if ( e.which === 9 ){ // TAB
            return;
        } else { // All other keys
            return false;
        }
        }
    });

    });

    };

    // Old browser fallback
    if ( !multipleSupport ) {
    $( document ).on('change', 'input.customfile', function() {

    var $this = $(this),
        // Create a unique ID so we
        // can attach the label to the input
        uniqId = 'customfile_'+ (new Date()).getTime(),
        $wrap = $this.parent(),

        // Filter empty input
        $inputs = $wrap.siblings().find('.file-upload-input')
            .filter(function(){ return !this.value }),

        $file = $('<input type="file" id="'+ uniqId +'" name="'+ $this.attr('name') +'"/>');

    // 1ms timeout so it runs after all other events
    // that modify the value have triggered
    setTimeout(function() {
        // Add a new input
        if ( $this.val() ) {
        // Check for empty fields to prevent
        // creating new inputs when changing files
        if ( !$inputs.length ) {
            $wrap.after( $file );
            $file.customFile();
        }
        // Remove and reorganize inputs
        } else {
        $inputs.parent().remove();
        // Move the input so it's always last on the list
        $wrap.appendTo( $wrap.parent() );
        $wrap.find('input').focus();
        }
    }, 1);

    });
    }

    }(jQuery));

    $('input[type=file]').customFile();
</script>
<script type="text/javascript">
    $(document).ready(function() {
    $("div.desc").hide();
    $("input[name$='is_nominating_authority']").click(function() {
        var test = $(this).val();
        $("div.desc").hide();

        if(test == 1){
            $('#twoCarDiv').show();
        }else{
            $('#twoCarDiv').hide();
        }
        // $("#" + test).show();
    });
    });
    $(document).ready(function() {
    $("div.desc2").hide();
    $("input[name$='is_sponsoring_agency']").click(function() {
        var test = $(this).val();
        $("div.desc2").hide();
        if(test == 1){
            $('#twoCarDiv-two').show();
        }else{
            $('#twoCarDiv-two').hide();
        }
        // $("#" + test).show();
    });
    });

    $(document).ready(function() {
    $("div.r_desc").hide();
    $("input[name$='r_nom-authority']").click(function() {
        var test = $(this).val();
        $("div.r_desc").hide();
        $("#" + test).show();
    });
    });
    $(document).ready(function() {
    $("div.r_desc2").hide();
    $("input[name$='r_sp-agency']").click(function() {
        var test = $(this).val();
        $("div.r_desc2").hide();
        $("#" + test).show();
    });
    });

</script>


@endsection

