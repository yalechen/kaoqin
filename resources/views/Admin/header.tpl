<div class="header-section">
    <!--logo and logo icon start-->
    <div class="logo dark-logo-bg hidden-xs hidden-sm">
        <a href="{route('AdminIndex')}">
            <img src="{asset('upload_files/init/logo20x20.png')}" alt="">
            <!--<i class="fa fa-maxcdn"></i>-->
            <span class="brand-name">考勤OS</span>
        </a>
    </div>

    <div class="icon-logo dark-logo-bg hidden-xs hidden-sm">
        <a href="index.html">
            <img src="{asset('upload_files/init/logo20x20.png')}" alt="">
            <!--<i class="fa fa-maxcdn"></i>-->
        </a>
    </div>
    <!--logo and logo icon end-->

    <!--toggle button start-->
    <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
    <!--toggle button end-->

    <!--mega menu start-->
    <div id="navbar-collapse-1" class="navbar-collapse collapse yamm mega-menu">
        <ul class="nav navbar-nav">
            <!-- Classic list -->
            <li class="dropdown"><a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">菜单导航 <b
                    class=" fa fa-angle-down"></b></a>
                <ul class="dropdown-menu wide-full">
                    <li>
                        <!-- Content container to add padding -->
                        <div class="yamm-content">
                            <div class="row">
                                <ul class="col-sm-2 list-unstyled">
                                    <li><p class="title">组织架构</p></li>
                                    <li><a href="{route('OrgIndex')}"> 机构管理</a></li>
                                    <li><a href="{route('DeptIndex')}"> 部门管理</a></li>
                                </ul>
                                <ul class="col-sm-2 list-unstyled">
                                    <li><p class="title">CRM管理</p></li>
                                    <li><a href="{route('UserIndex')}"> 用户管理</a></li>
                                    <li><a href="{route('CustIndex')}"> 商户管理</a></li>
                                    <li><a href="{route('CustLevelIndex')}"> 商户等级管理</a></li>
                                </ul>
                                <ul class="col-sm-2 list-unstyled">
                                    <li><p class="title">考勤管理</p></li>
                                    <li><a href="{route('LeaveTypeIndex')}"><i class="fa fa-anchor text-muted"></i> 请假类型</a></li>
                                    <li><a href="{route('LeaveExcelImport')}"><i class="fa fa-desktop text-muted"></i> Excel批量导入</a></li>
                                    <li><a href="{route('LeaveIndex')}"><i class="fa fa-laptop text-muted"></i> 请假列表</a></li>
                                    <li><a href="{route('OvertimeIndex')}"><i class="fa fa-laptop text-muted"></i> 加班管理</a></li>
                                </ul>
                                <ul class="col-sm-2 list-unstyled">
                                    <li><p class="title">任务管理</p></li>
                                    <li><a href="{route('TaskGeneralIndex')}"> 常规任务</a></li>
                                    <li><a href="{route('TaskAssignIndex')}"> 临时任务</a></li>
                                </ul>
                                <ul class="col-sm-2 list-unstyled">
                                    <li><p class="title">报表统计</p></li>
                                    <li><a href="javascript:;"> 我的任务表</a></li>
                                    <li><a href="javascript:;"> 拜访记录表</a></li>
                                    <li><a href="javascript:;"> 里程统计</a></li>
                                    <li><a href="javascript:;"> 签到报表</a></li>
                                    <li><a href="javascript:;"> 加班报表</a></li>
                                    <li><a href="javascript:;"> 请假报表</a></li>
                                    <li><a href="javascript:;"> 总体考勤报表</a></li>
                                </ul>
								<ul class="col-sm-2 list-unstyled">
                                    <li><p class="title">系统设置</p></li>
                                    <li><a href="{route('RoleIndex')}"> 角色管理</a></li>
                                    <li><a href="javascript:;"> 权限管理</a></li>
                                    <li><a href="javascript:;"> 角色权限指派</a></li>
                                    <li><a href="javascript:;"> 角色成员指派</a></li>
                                    <li><a href="javascript:;"> 操作日志</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!--mega menu end-->
    <div class="notification-wrap">
    <!--left notification start-->
    <div class="left-notification">
    <ul class="notification-menu">
    <!--task info start-->
    <li class="d-none">
        <a href="javascript:;" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
            <i class="fa fa-tasks"></i>
            <span class="badge bg-success">9</span>
        </a>

        <div class="dropdown-menu dropdown-title">
            <div class="title-row">
                <h5 class="title green">您有9个需要完成的任务</h5>
                <a href="javascript:;" class="btn-success btn-view-all">查看</a>
            </div>
            <div class="notification-list task-list">
                <a href="javascript:;">
                    <span class="icon"><i class="fa fa-paw green-color"></i></span>
                    <span class="task-info">[2015-07]符强常规拜访V1级门店<small> 还有5天就要结束了</small></span>
                </a>
                <a href="javascript:;">
                    <span class="icon">
                        <i class="fa fa-line-chart blue-color"></i>
                    </span>
                    <span class="task-info">[2015-07]超级管理员临时拜访任务<small> 您才完成30%</small>
                    <div class="progress progress-striped">
                        <div style="width: 66%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="66"
                             role="progressbar" class="progress-bar progress-bar-info"></div>
                    </div>
                    </span>
                </a>
                <a href="javascript:;">
                    <span class="icon ">
                        <i class="fa fa-heart purple-color"></i>
                    </span>
                    <span class="task-info">Tawseef
                    <small> Like your task 10 min ago</small>
                        </span>

                </a>
                <a href="javascript:;">
                    <span class="icon ">
                        <i class="fa fa-check green-color"></i>
                    </span>
                    <span class="task-info">Anjelina Gomez
                    <small>completed his task Yesterday</small>
                        </span>
                </a>
                <a href="javascript:;">
                    <span class="icon ">
                        <i class="fa fa-comments-o"></i>
                    </span>
                    <span class="task-info">Franklin Anderson
                    <small>commented on your task 3 Days ago</small>
                    </span>
                </a>
            </div>
        </div>
    </li>
    <!--task info end-->

    <!--notification info start-->
    <li>
        <a href="javascript:;" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="badge bg-warning">4</span>
        </a>

        <div class="dropdown-menu dropdown-title ">

            <div class="title-row">
                <h5 class="title yellow">您有4个消息</h5>
                <a href="javascript:;" class="btn-warning btn-view-all">查看</a>
            </div>
            <div class="notification-list-scroll sidebar">
                <div class="notification-list mail-list not-list">
                    <a href="javascript:;" class="single-mail">
                        <span class="icon bg-primary">
                            <i class="fa fa-envelope-o"></i>
                        </span>
                        <strong>New User Registration</strong>

                        <p>
                            <small>Just Now</small>
                        </p>
                        <span class="un-read tooltips" data-original-title="Mark as Read" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-circle"></i>
                        </span>
                    </a>
                    <a href="javascript:;" class="single-mail">
                        <span class="icon bg-success">
                            <i class="fa fa-comments-o"></i>
                        </span>
                        <strong> Private message Send</strong>

                        <p>
                            <small>30 Mins Ago</small>
                        </p>
                        <span class="un-read tooltips" data-original-title="Mark as Read" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-circle"></i>
                        </span>
                    </a>
                    <a href="javascript:;" class="single-mail">
                        <span class="icon bg-warning">
                            <i class="fa fa-warning"></i>
                        </span> Application Error
                        <p>
                            <small> 2 Days Ago</small>
                        </p>
                        <span class="read tooltips" data-original-title="Mark as Unread" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-circle-o"></i>
                        </span>
                    </a>
                    <a href="javascript:;" class="single-mail">
                        <span class="icon bg-dark">
                           <i class="fa fa-database"></i>
                        </span> Database Overloaded 24%
                        <p>
                            <small>1 Week Ago</small>
                        </p>
                        <span class="read tooltips" data-original-title="Mark as Unread" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-circle-o"></i>
                        </span>
                    </a>
                    <a href="javascript:;" class="single-mail">
                        <span class="icon bg-danger">
                            <i class="fa fa-warning"></i>
                        </span>
                        <strong>Server Failed Notification</strong>

                        <p>
                            <small>10 Days Ago</small>
                        </p>
                        <span class="un-read tooltips" data-original-title="Mark as Read" data-toggle="tooltip" data-placement="left">
                            <i class="fa fa-circle"></i>
                        </span>
                    </a>

                </div>
            </div>
        </div>
    </li>
    <!--notification info end-->
    </ul>
    </div>
    <!--left notification end-->


    <!--right notification start-->
    <div class="right-notification">
        <ul class="notification-menu">
            <li>
                <form class="search-content" action="index.html" method="post">
                    <input type="text" class="form-control" name="keyword" placeholder="Search...">
                </form>
            </li>

            <li>
                <a href="javascript:;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <img src="{Auth::user()->avatar_path|default:asset('img/avatar-mini.jpg')}" alt="{Auth::user()->name}头像">{Auth::user()->name}
                    <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu purple pull-right">
                    <li><a href="{route('UserEdit',['id'=>Auth::user()->id])}">  个人资料</a></li>
                    <li><a href="{route('ConfigsIndex')}"><span class="label bg-info pull-right">new</span>  系统设置</a></li>
                    <li><a href="{route('ArticleIndex')}">  文章管理</a></li>
                    <li><a href="{route('FeedbackIndex')}">  意见反馈</a></li>
                    <li><a href="{route('AdminLogout')}"><i class="fa fa-sign-out pull-right"></i> 退出</a></li>
                    <!-- <li>
                        <a href="javascript:;">
                            <span class="badge bg-danger pull-right">40%</span>
                            <span>设置</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            <li>
                <!-- <div class="sb-toggle-right">
                    <i class="fa fa-indent"></i>
                </div> -->
            </li>

        </ul>
    </div>
    <!--right notification end-->
    </div>
</div>