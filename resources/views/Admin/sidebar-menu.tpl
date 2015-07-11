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
            <h3 class="navigation-title">功能管理</h3>
        </li>
        <li class="menu-list {if in_array(Route::currentRouteName(),['OrgIndex','OrgEdit','DeptIndex','DeptEdit'])}nav-active{/if}">
        	<a href="javascript:;"><i class="fa fa-bank"></i> <span>组织架构  </span></a>
            <ul class="child-list">
                <li {if in_array(Route::currentRouteName(),['OrgIndex','OrgEdit'])}class="active"{/if}>
                	<a href="{route('OrgIndex')}"> 机构管理</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['DeptIndex','DeptEdit'])}class="active"{/if}>
                	<a href="{route('DeptIndex')}"> 部门管理</a>
                </li>
            </ul>
        </li>
        
        <li class="menu-list {if in_array(Route::currentRouteName(),['UserIndex','UserEdit','CustIndex','CustEdit','CustLevelIndex','CustLevelEdit'])}nav-active{/if}">
        	<a href="javascript:;"><i class="fa fa-user"></i> <span>CRM管理  </span></a>
            <ul class="child-list">
                <li {if in_array(Route::currentRouteName(),['UserIndex','UserEdit'])}class="active"{/if}>
                	<a href="{route('UserIndex')}"> 用户管理</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['CustIndex','CustEdit'])}class="active"{/if}>
                	<a href="{route('CustIndex')}"> 商户管理</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['CustLevelIndex','CustLevelEdit'])}class="active"{/if}>
                	<a href="{route('CustLevelIndex')}"> 商户等级管理</a>
                </li>
            </ul>
        </li>
        
        <li class="menu-list {if in_array(Route::currentRouteName(),['LeaveTypeIndex','LeaveTypeEdit','LeaveExcelIndex','OvertimeIndex','OvertimeEdit','LeaveIndex','LeaveEdit'])}nav-active{/if}">
        	<a href="javascript:;"><i class="fa fa-calendar"></i> <span>考勤管理</span></a>
            <ul class="child-list">
                <li {if in_array(Route::currentRouteName(),['LeaveTypeIndex','LeaveTypeEdit'])}class="active"{/if}>
                	<a href="{route('LeaveTypeIndex')}"> 请假类型</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['LeaveExcelIndex'])}class="active"{/if}>
                	<a href="{route('LeaveExcelImport')}"> Excel批量导入</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['LeaveIndex','LeaveEdit'])}class="active"{/if}>
                	<a href="{route('LeaveIndex')}"> 请假列表</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['OvertimeIndex','OvertimeEdit'])}class="active"{/if}>
                	<a href="{route('OvertimeIndex')}"> 加班管理{if $wait_audit_overtime_nums gt 0}<span class="label noti-arrow bg-danger pull-right">{$wait_audit_overtime_nums}待审</span>{/if} </a>
                </li>
            </ul>
        </li>
        
        <li class="menu-list {if in_array(Route::currentRouteName(),['TaskGeneralIndex','TaskAssignIndex','TaskGeneralDetail','TaskAssignEdit'])}nav-active{/if}">
        	<a href="javascript:;"><i class="fa fa-bookmark"></i> <span>任务管理</span></a>
            <ul class="child-list">
                <li {if in_array(Route::currentRouteName(),['TaskGeneralIndex','TaskGeneralDetail'])}class="active"{/if}>
                	<a href="{route('TaskGeneralIndex')}"> 常规任务{if $unfinish_task_general_nums gt 0}<span class="badge noti-arrow bg-success pull-right">{$unfinish_task_general_nums}</span> {/if}</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['TaskAssignIndex','TaskAssignEdit'])}class="active"{/if}>
                	<a href="{route('TaskAssignIndex')}"> 临时任务{if $unfinish_task_assign_nums gt 0}<span class="badge noti-arrow bg-success pull-right">{$unfinish_task_assign_nums}</span> {/if}</a>
                </li>
            </ul>
        </li>
        
        <li class="menu-list {if in_array(Route::currentRouteName(),['FeedbackIndex','ArticleIndex','ArticleEdit','ConfigsIndex','RoleIndex','RoleEdit','PurviewSeed','PurviewIndex'])}nav-active{/if}">
        	<a href="javascript:;"><i class="fa fa-gear"></i> <span>系统设置</span></a>
            <ul class="child-list">
            	<li {if in_array(Route::currentRouteName(),['ConfigsIndex'])}class="active"{/if}>
                	<a href="{route('ConfigsIndex')}"> 基本设置</a>
                </li>
            	<li {if in_array(Route::currentRouteName(),['ArticleIndex','ArticleEdit'])}class="active"{/if}>
                	<a href="{route('ArticleIndex')}"> 文章管理</a>
                </li>
            	<li {if in_array(Route::currentRouteName(),['FeedbackIndex'])}class="active"{/if}>
                	<a href="{route('FeedbackIndex')}"> 意见反馈</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['RoleIndex','RoleEdit'])}class="active"{/if}>
                	<a href="{route('RoleIndex')}"> 角色管理</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['PurviewIndex'])}class="active"{/if}>
                	<a href="{route('PurviewIndex')}"> 权限管理</a>
                </li>
                <li {if in_array(Route::currentRouteName(),['PurviewSeed'])}class="active"{/if}>
                	<a href="{route('PurviewSeed')}"> 权限填充</a>
                </li>
                <li>
                	<a href="###"> 角色权限指派</a>
                </li>
                <li>
                	<a href="###"> 操作日志</a>
                </li>
            </ul>
        </li>
        
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
    </ul>
    <!--sidebar nav end-->

    <!--sidebar widget start-->
    <div class="sidebar-widget">
    </div>
    <!--sidebar widget end-->
</div>
