<!--responsive view logo start-->
<div class="logo dark-logo-bg visible-xs-* visible-sm-*">
    <a href="index.html">
        <img src="img/logo-icon.png" alt="">
        <!--<i class="fa fa-maxcdn"></i>-->
        <span class="brand-name">考勤系统后台</span>
    </a>
</div>
<!--responsive view logo end-->

<div class="sidebar-left-info">
    <!-- visible small devices start-->
    <div class=" search-field">  </div>
    <!-- visible small devices end-->

    <!--sidebar nav start-->
    <ul class="nav nav-pills nav-stacked side-navigation">
        <li>
            <h3 class="navigation-title">组织架构</h3>
        </li>
        <li {if Route::currentRouteName() eq 'OrgIndex'}class="active"{/if}><a href="{route('OrgIndex')}"><i class="fa fa-home"></i> <span>机构管理</span></a></li>
        <li {if Route::currentRouteName() eq 'DeptIndex'}class="active"{/if}><a href="{route('DeptIndex')}"><i class="fa fa-home"></i> <span>部门管理</span></a></li>
        
        
        <li>
            <h3 class="navigation-title">考勤管理</h3>
        </li>
        <li class="menu-list"><a href=""><i class="fa fa-cogs"></i> <span>请假管理 <span class="badge noti-arrow bg-success pull-right">3</span> </span></a>
            <ul class="child-list">
                <li><a href="grid.html"> 请假列表</a></li>
                <li><a href="calendar.html"> 请假审批</a></li>
                <li><a href="timeline.html"> 假别管理 </a></li>
                <li><a href="gallery.html"> 批量导入 </a></li>
            </ul>
        </li>
        <li class="menu-list"><a href="javascript:;"><i class="fa fa-th-list"></i> <span>加班管理</span></a>
            <ul class="child-list">
                <li><a href="table-static.html"> 加班列表</a></li>
                <li><a href="table-dynamic.html"> 加班审批</a></li>
            </ul>
        </li>
        <li class="menu-list"><a href=""><i class="fa fa-tasks"></i> <span>里程管理</span></a>
            <ul class="child-list">
                <li><a href="form-layout.html"> 里程列表</a></li>
                <li><a href="advanced-components.html"> 行驶轨迹</a></li>
            </ul>
        </li>
        <li class="menu-list"><a href=""><i class="fa fa-bar-chart-o"></i> <span>位置管理</span></a>
            <ul class="child-list">
                <li><a href="flot-chart.html"> 实时位置</a></li>
                <li><a href="morris-chart.html"> 轨迹列表</a></li>
            </ul>
        </li>
        <li class="menu-list"><a href=""><i class="fa fa-bar-chart-o"></i> <span>考勤管理</span></a>
            <ul class="child-list">
                <li><a href="flot-chart.html"> 考勤列表</a></li>
                <li><a href="morris-chart.html"> 考勤导出</a></li>
            </ul>
        </li>
        
        
        <li>
            <h3 class="navigation-title">统计分析</h3>
        </li>
        <li class="menu-list"><a href="javascript:;"><i class="fa fa-envelope-o"></i> <span>Email <span class="label noti-arrow bg-danger pull-right">4 Unread</span> </span></a>
            <ul class="child-list">
                <li><a href="inbox.html"> 渠道拜访</a></li>
                <li><a href="inbox-details.html"> 考勤分析</a></li>
                <li><a href="inbox-compose.html"> 里程统计</a></li>
            </ul>
        </li>
        
        <li>
            <h3 class="navigation-title">报表统计</h3>
        </li>
        <li class="menu-list">
            <a href=""><i class="fa fa-laptop"></i>  <span>任务报表</span></a>
            <ul class="child-list">
                <li><a href="###"> 拜访记录表</a></li>
                <li><a href="###"> 里程统计</a></li>
            </ul>
        </li>
        <li class="menu-list"><a href=""><i class="fa fa-book"></i> <span>考勤报表</span></a>
            <ul class="child-list">
                <li><a href="###"> 签到报表</a></li>
                <li><a href="###"> 加班报表</a></li>
                <li><a href="###"> 请假报表</a></li>
                <li><a href="###"> 总体考勤报表</a></li>
            </ul>
        </li>
        <li><a href="###"><i class="fa fa-home"></i> <span>门店统计</span></a></li>
    </ul>
    <!--sidebar nav end-->

    <!--sidebar widget start-->
    <div class="sidebar-widget">
    </div>
    <!--sidebar widget end-->
</div>
