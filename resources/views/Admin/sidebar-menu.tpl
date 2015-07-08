<!--responsive view logo start-->
<div class="logo dark-logo-bg visible-xs-* visible-sm-*">
    <a href="index.html">
        <img src="{asset('upload_files/init/logo20x20.png')}" alt="">
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
        <li {if in_array(Route::currentRouteName(),['OrgIndex','OrgEdit'])}class="active"{/if}><a href="{route('OrgIndex')}"><i class="fa fa-bank"></i> <span>机构管理</span></a></li>
        <li {if in_array(Route::currentRouteName(),['DeptIndex','DeptEdit'])}class="active"{/if}><a href="{route('DeptIndex')}"><i class="fa fa-shield"></i> <span>部门管理</span></a></li>
        
        
        <li>
            <h3 class="navigation-title">CRM管理</h3>
        </li>
        <li {if in_array(Route::currentRouteName(),['UserIndex','UserEdit'])}class="active"{/if}><a href="{route('UserIndex')}"><i class="fa fa-user"></i> <span>用户管理</span></a></li>
        <li class="menu-list {if substr(Route::currentRouteName(),0,4) eq 'Cust'}nav-active{/if}">
        	<a href=""><i class="fa fa-money"></i> <span>商户管理  </span></a>
            <ul class="child-list">
                <li {if in_array(Route::currentRouteName(),['CustIndex','CustEdit','SearchCustUsers'])}class="active"{/if}>
                	<a href="{route('CustIndex')}"> 商户管理</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['CustLevelIndex','CustLevelEdit'])}class="active"{/if}>
                	<a href="{route('CustLevelIndex')}"> 商户等级管理</a>
                </li>
            </ul>
        </li>
        
        <li>
            <h3 class="navigation-title">考勤管理</h3>
        </li>
        <li class="menu-list {if substr(Route::currentRouteName(),0,5) eq 'Leave'}nav-active{/if}"><a href="javascript:;"><i class="fa fa-calendar"></i> <span>请假管理</span></a>
            <ul class="child-list">
                <li {if in_array(Route::currentRouteName(),['LeaveTypeIndex','LeaveTypeEdit'])}class="active"{/if}>
                	<a href="{route('LeaveTypeIndex')}"> 请假类型</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['LeaveExcelIndex'])}class="active"{/if}>
                	<a href="{route('LeaveExcelImport')}"> Excel批量导入</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['LeaveIndex'])}class="active"{/if}>
                	<a href="{route('LeaveIndex')}"> 请假列表</a>
                </li>
            </ul>
        </li>
        <li {if Route::currentRouteName() eq 'OvertimeIndex'}class="active"{/if}><a href="{route('OvertimeIndex')}"><i class="fa fa-bullhorn"></i> <span>加班管理 
        	{if $wait_audit_overtime_nums gt 0}<span class="label noti-arrow bg-danger pull-right">{$wait_audit_overtime_nums}待审</span>{/if} </span></a>
        </li>
        <li>
            <h3 class="navigation-title">任务管理</h3>
        </li>
        <li {if Route::currentRouteName() eq 'TaskGeneralIndex'}class="active"{/if}><a href="{route('TaskGeneralIndex')}"><i class="fa fa-bookmark"></i> <span>常规任务
        	{if $unfinish_task_general_nums gt 0}<span class="badge noti-arrow bg-success pull-right">{$unfinish_task_general_nums}</span> {/if}</span></a></li>
        <li {if Route::currentRouteName() eq 'TaskAssignIndex'}class="active"{/if}><a href="{route('TaskAssignIndex')}"><i class="fa fa-bookmark-o"></i> <span>临时任务
        	{if $unfinish_task_assign_nums gt 0}<span class="badge noti-arrow bg-success pull-right">{$unfinish_task_assign_nums}</span> {/if}</span></a></li>
        
        <li>
            <h3 class="navigation-title">报表统计</h3>
        </li>
        <li class="menu-list">
            <a href=""><i class="fa fa-bar-chart-o"></i>  <span>任务报表</span></a>
            <ul class="child-list">
                <li><a href="###"> 我的任务表</a></li>
                <li><a href="###"> 拜访记录表</a></li>
                <li><a href="###"> 里程统计</a></li>
            </ul>
        </li>
        <li class="menu-list"><a href=""><i class="fa fa-th"></i> <span>考勤报表</span></a>
            <ul class="child-list">
                <li><a href="###"><i class="fa fa-bar-chart-o"></i> <span>签到报表</span></a></li>
                <li><a href="###"><i class="fa fa-th"></i> <span>加班报表</span></a></li>
                <li><a href="###"><i class="fa fa-th-list"></i> <span>请假报表</span></a></li>
                <li><a href="###"><i class="fa fa-bar-chart-o"></i> <span>总体考勤报表</span></a></li>
            </ul>
        </li>
        <li><a href="###"><i class="fa fa-delicious"></i> <span>门店统计</span></a></li>
        
        <li>
            <h3 class="navigation-title">系统设置</h3>
        </li>
        <li {if Route::currentRouteName() eq 'RoleIndex'}class="active"{/if}><a href="{route('RoleIndex')}"><i class="fa fa-delicious"></i> <span>角色管理</span></a></li>
        <li {if Route::currentRouteName() eq 'PurviewIndex'}class="active"{/if}><a href="{route('PurviewIndex')}"><i class="fa fa-delicious"></i> <span>权限管理</span></a></li>
        <li {if Route::currentRouteName() eq 'PurviewSeed'}class="active"{/if}><a href="{route('PurviewSeed')}"><i class="fa fa-delicious"></i> <span>权限填充</span></a></li>
        <li><a href="###"><i class="fa fa-delicious"></i> <span>角色权限指派</span></a></li>
        <li><a href="###"><i class="fa fa-delicious"></i> <span>角色成员指派</span></a></li>
        <li><a href="###"><i class="fa fa-delicious"></i> <span>操作日志</span></a></li>
    </ul>
    <!--sidebar nav end-->

    <!--sidebar widget start-->
    <div class="sidebar-widget">
    </div>
    <!--sidebar widget end-->
</div>
