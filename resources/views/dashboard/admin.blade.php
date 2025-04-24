@extends('layouts.admin_main')
@section('title','Dashboard')
@section('breadcrumb','Dashboard')

@section('content')
    <link href="{{asset('assets/css/dashboard.css')}}" rel="stylesheet" type="text/css" />
    <div class="page-content-wrapper homepage">
        <div class="page-content form-main-2">
            <!-- new welcome -->

            <!-- <div class="row pl-2"> -->
                <div class="col-lg-4 col-md-4 col-12 mb-1">
                    <h4 class="mt-1"> Welcome to, <span class="color-theme bold">Dashboard</span> </h4>
                </div>
                <!-- <div class="col ">
                    <div class="row pull-right pr-4 cadre">
                        
                    </div>
                </div> -->
            </div>

            <!-- new welcome -->

        
           







        </div>



    </div>


    </div>

@endsection

@section('jsfile')

    <!-- <script>
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
    </script> -->
@endsection
