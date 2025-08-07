<!-- start js include path -->

<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

<script src="{{asset('assets/js/app.js')}}"></script>
<script src="{{asset('assets/js/layout.js')}}"></script>

<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- select 2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" integrity="sha256-AFAYEOkzB6iIKnTYZOdUf9FFje6lOTYdwRJKwTN5mks=" crossorigin="anonymous"></script>

<!-- datatable buttons -->
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>

<!-- datatable buttons -->
<script type="text/javascript">

    $( document ).ajaxComplete(function() {
        // Required for Bootstrap tooltips in DataTables
        $('[data-toggle="tooltip"]').tooltip({
            "html": true,
            "delay": {"show": 10, "hide": 0},
        });
    });

    $(function () {

  $('select').each(function () {
    $(this).select2({
      theme: 'bootstrap4',
      width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
       placeholder: $(this).data('placeholder'),
      allowClear: Boolean($(this).data('allow-clear')),
      closeOnSelect:true,
    //   closeOnSelect: !$(this).attr('multiple'),
    });
  });


});

    var table = $('#table_id').DataTable( {
        ordering:  false
    });

</script>

<script type="text/javascript">
      	$(document).ready(function(){

            $('.nav-list li a').click(function(){
                $('li a').removeClass("active");
                $(this).addClass("active");
            });
        });

        function showAlertMasg(icon,title,text,footer=false){

            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                footer: footer
            });

        }//end of function
</script>

<!-- @if(Session::has('success'))
<script type="text/javascript">

    var masg = '<?php echo Session::get('success') ;?>';
    showAlertMasg('success','Success',masg);

</script>
@endif

@if(Session::has('error'))
<script type="text/javascript">
    var masg = '<?php echo Session::get('error'); ?>';
    showAlertMasg('error','Error',masg);
</script> -->
@endif
