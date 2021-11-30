
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">


            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ route('admin.dashboard') }}"> <i class="menu-icon fa fa-dashboard"></i>{{ trans('global.admin_dashboard') }} </a>
                    </li>
                    <h3 class="menu-title"></h3><!-- /.menu-title -->
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-users"></i>{{ trans('titles.userManagement.title') }}</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-users"></i><a href="{{ route('admin.users.index') }}">{{ trans('titles.user.title') }}</a></li>
                            <li><i class="fa fa-users"></i><a href="{{ route('admin.users.create') }}">{{ trans('global.create') }}</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a  href="{{ route('admin.categories.index') }}" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-table"></i>{{ trans('titles.category.main_title') }}</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-table"></i><a href="{{ route('admin.categories.index') }}">{{ trans('titles.category.title') }}</a></li>

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="{{ route("admin.questions.index") }}" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-question"></i>{{ trans('titles.question.main_title') }}</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-question"></i><a href="{{ route("admin.questions.index") }}">{{ trans("titles.question.title") }}</a></li>

                        </ul>
                    </li>



                    <li class="menu-item-has-children dropdown">
                        <a href="{{ route("admin.options.index") }}" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-check "></i>{{ trans('titles.option.main_title') }}</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-fort-awesome"></i><a href="{{ route("admin.options.index") }}">{{ trans('titles.option.title') }}</a></li>

                        </ul>
                    </li>
                    <li class="menu-item-has-children dropdown">

                        <a href="{{ route("admin.results.index") }}" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-trophy "></i>{{ trans('titles.result.title') }}</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-trophy"></i><a href="{{ route("admin.results.index") }}">{{ trans('titles.result.title') }}</a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="log-out-btn" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> <i class="menu-icon fa fa-sign-out"></i>{{ trans('global.logout') }} </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                 @csrf;
                         </form>
                    </li>
                    <h3 class="menu-title">Extras</h3><!-- /.menu-title -->
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-glass"></i>Pages</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-sign-in"></i><a href="page-login.html">Login</a></li>
                            <li><i class="menu-icon fa fa-sign-in"></i><a href="page-register.html">Register</a></li>
                            <li><i class="menu-icon fa fa-paper-plane"></i><a href="pages-forget.html">Forget Pass</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

