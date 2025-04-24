<!-- start sidebar menu -->
<div class="col-sm-12 col-md-4  affix-sidebar">
<div class="sidebar-nav">
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
        </div>
        <div class="navbar-collapsee collapsee sidebar-navbar-collapsee">
            <ul class="nav navbar-nav" id="sidenav01">
            <li>
                <a href="#" data-toggle="collapse" data-target="#toggleDemo" data-parent="#sidenav01" class="collapsed" aria-hidden="true">
                Establishment<span class="caret pull-right"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                </a>
                <div class="collapse" id="toggleDemo" style="height: 0px;">
                    <ul class="nav nav-list">
                        <li><a href="{{route('establishment.study')}}" class="{{$inner_side == 1 ? 'active': ''}}">Study within Pakistan</a></li>
                        <li><a href="{{route('establishment.passport')}}" class="{{$inner_side == 2 ? 'active': ''}}">Issuance/Renewal of Passport</a></li>
                        <li><a href="{{route('establishment.advertised')}}" class="{{$inner_side == 3 ? 'active': ''}}">Advertised Vacancies</a></li>
                        <li><a href="{{route('establishment.travel')}}" class="{{$inner_side == 4 ? 'active': ''}}">Travel Abroad</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#" data-toggle="collapse" data-target="#toggleDemo2" data-parent="#sidenav01" class="collapsed">
                Discipline <span class="caret pull-right"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                </a>
                <div class="collapse" id="toggleDemo2" style="height: 0px;">
                    <ul class="nav nav-list">
                        <li><a href="{{route('discipline.marriage')}}" class="{{$inner_side == 5 ? 'active': ''}}">Marriage with Foreign Nationals</a></li>
                        <li><a href="{{route('discipline.book')}}" class="{{$inner_side == 6 ? 'active': ''}}">Publication of Book</a></li>
                        <li><a href="{{route('discipline.paper')}}" class="{{$inner_side == 7 ? 'active': ''}}">Permission to write in Newspaper/Periodicals</a></li>
                        <li><a href="{{route('discipline.researchpaper')}}" class="{{$inner_side == 8 ? 'active': ''}}">Permission to write in Journal/
                        Research paper</a>
                        </li>
                        <li><a href="{{route('discipline.journalpaper')}}" class="{{$inner_side == 9 ? 'active': ''}}">Contribution in Journal/ Research
                        papers</a>
                        </li>
                        <li><a href="{{route('discipline.membership')}}" class="{{$inner_side == 10 ? 'active': ''}}">Membership of Associations</a></li>
                        <li><a href="{{route('discipline.media')}}" class="{{$inner_side == 11 ? 'active': ''}}">Permission to appear in Electronic
                        Media, Social, Media Channels,
                        Communication with Press</a>
                        </li>
                        <li><a href="{{route('discipline.trade')}}" class="{{$inner_side == 12 ? 'active': ''}}">Private Trade, Employment/ Work</a></li>
                        <li><a href="{{route('discipline.promotion')}}" class="{{$inner_side == 13 ? 'active': ''}}">Promotion & Management of
                        companies</a>
                        </li>
                        <li><a href="{{route('discipline.teaching')}}" class="{{$inner_side == 14 ? 'active': ''}}">Teaching/ Seminar/ Symposium &
                        webinar (within Pakistan)</a>
                        </li>
                        <li><a href="{{route('discipline.teaching_online')}}" class="{{$inner_side == 15 ? 'active': ''}}">Teaching/ Seminar/ Symposium
                        (on line)</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#" data-toggle="collapse" data-target="#toggleDemo3" data-parent="#sidenav01" class="collapsed">
                Trainings<span class="caret pull-right"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                </a>
                <div class="collapse" id="toggleDemo3" style="height: 0px;">
                    <ul class="nav nav-list">
                        <li><a href="{{route('trainings.deputation')}}" class="{{$inner_side == 16 ? 'active': ''}}">Apply and Avail deputation in international Organizations</a></li>
                        <li><a href="{{route('trainings.deployment')}}" class="{{$inner_side == 17 ? 'active': ''}}">Deployment/Secondment in UN Mission Abroad</a></li>
                        <li><a href="{{route('trainings.noc_to_apply')}}" class="{{$inner_side == 18 ? 'active': ''}}">NOC to Apply</a></li>
                        <li><a href="{{route('trainings.noc_to_avail')}}" class="{{$inner_side == 19 ? 'active': ''}}">NOC to Avail</a></li>
                        <li><a href="{{route('trainings.ex_post_facto')}}" class="{{$inner_side == 20 ? 'active': ''}}">Ex-post facto Approval for Grant of NOC to apply and Avail</a></li>
                    </ul>
                </div>
            </li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
</div>
<!-- end sidebar menu -->
