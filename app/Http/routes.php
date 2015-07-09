<?php

Route::get('/',function(){

});

// 通用上传文件
Route::post('upload-file', [
    'as' => 'UploadFile',
    'name' => '通用上传文件',
    'uses' => 'StorageController@postFile'
]);
// 表单上传文件
Route::post('form-upload-file', [
    'as' => 'FormUploadFile',
    'name' => '表单上传文件',
    'uses' => 'StorageController@formUploadFile'
]);
// 获取文件
Route::get('file', [
    'as' => 'FilePull',
    'name' => '获取文件',
    'uses' => 'StorageController@getFile'
]);

// 后台管理
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
],
    function ()
    {
        // 获取城市列表
        Route::get('city', [
            'as' => 'CityPull',
            'name' => '获取城市列表',
            'parent' => 'SystemModule',
            'uses' => 'BaseController@getCity'
        ]);
        // 获取区县列表
        Route::get('district', [
            'as' => 'DistrictPull',
            'name' => '获取区县列表',
            'parent' => 'SystemModule',
            'uses' => 'BaseController@getDistrict'
        ]);

        // 退出
        Route::get('logout', [
            'as' => 'AdminLogout',
            'name' => '登出',
            'parent' => 'SystemModule',
            'uses' => 'IndexController@logout'
        ]);
        // 登录
        Route::get('login', [
            'as' => 'AdminGetLogin',
            'name' => '登录页',
            'parent' => 'SystemModule',
            'uses' => 'IndexController@getLogin'
        ]);
        // 登录
        Route::post('login', [
            'as' => 'AdminPostLogin',
            'name' => '登录处理',
            'parent' => 'SystemModule',
            'uses' => 'IndexController@postLogin'
        ]);
        // 发送找回密码邮件
        Route::post('mail', [
            'as' => 'AdminPostMail',
            'name' => '发送找回密码邮件',
            'parent' => 'SystemModule',
            'uses' => 'PasswordController@postEmail'
        ]);
        // 重置密码页
        Route::get('reset/{token}', [
            'as' => 'AdminGetReset',
            'name' => '重置密码页',
            'parent' => 'SystemModule',
            'uses' => 'PasswordController@getReset'
        ]);
        // 重置密码处理
        Route::post('reset', [
            'as' => 'AdminPostReset',
            'name' => '重置密码处理',
            'parent' => 'SystemModule',
            'uses' => 'PasswordController@postReset'
        ]);

        // 获取某菜单的下级权限
        Route::get('purview/menu-sub', [
            'name' => '获取权限菜单的子菜单',
            'as' => 'PurviewMenuSub',
            'parent' => 'PurviewSeed',
            'uses' => 'PurviewController@subMenu'
        ]);

        // 需要登录权限
        Route::group([
            'middleware' => 'auth.admin'
        ],
            function ()
            {
                // 后台首页
                Route::get('/', [
                    'as' => 'AdminIndex',
                    'name' => '后台首页',
                    'parent' => 'SystemModule',
                    'uses' => 'IndexController@index'
                ]);

                // 设置管理
                Route::group([
                    'prefix' => 'configs'
                ], function ()
                {
                    // 设置页
                    Route::get('/', [
                        'as' => 'ConfigsIndex',
                        'name' => '设置管理',
                        'parent' => 'SystemModule',
                        'uses' => 'ConfigsController@index'
                    ]);
                    // 保存设置
                    Route::post('save', [
                        'as' => 'ConfigsSave',
                        'name' => '设置保存',
                        'parent' => 'ConfigsIndex',
                        'uses' => 'ConfigsController@save'
                    ]);
                });

                // 角色管理
                Route::group([
                    'prefix' => 'role'
                ], function ()
                {
                    // 管理页
                    Route::get('/', [
                        'as' => 'RoleIndex',
                        'name' => '角色管理',
                        'parent' => 'SystemModule',
                        'uses' => 'RoleController@index'
                    ]);
                    // 角色编辑
                    Route::get('edit', [
                        'as' => 'RoleEdit',
                        'name' => '角色编辑',
                        'parent' => 'RoleIndex',
                        'uses' => 'RoleController@edit'
                    ]);
                    // 保存角色
                    Route::post('save', [
                        'as' => 'RoleSave',
                        'name' => '角色保存',
                        'parent' => 'RoleIndex',
                        'uses' => 'RoleController@save'
                    ]);
                    // 删除角色
                    Route::post('delete', [
                        'as' => 'RoleDelete',
                        'name' => '角色删除',
                        'parent' => 'RoleIndex',
                        'uses' => 'RoleController@delete'
                    ]);
                });

                // 权限管理
                Route::group([
                    'prefix' => 'purview'
                ], function ()
                {
                    // 管理页
                    Route::get('/', [
                        'as' => 'PurviewIndex',
                        'name' => '权限管理',
                        'parent' => 'SystemModule',
                        'uses' => 'PurviewController@index'
                    ]);
                    // 获取权限详情
                    Route::get('info', [
                        'name' => '权限详情',
                        'as' => 'PurviewInfo',
                        'parent' => 'PurviewIndex',
                        'uses' => 'PurviewController@info'
                    ]);
                    // 权限编辑
                    Route::get('edit', [
                        'as' => 'PurviewEdit',
                        'name' => '权限编辑',
                        'parent' => 'PurviewIndex',
                        'uses' => 'PurviewController@edit'
                    ]);
                    // 保存权限
                    Route::post('save', [
                        'as' => 'PurviewSave',
                        'name' => '权限保存',
                        'parent' => 'PurviewIndex',
                        'uses' => 'PurviewController@save'
                    ]);
                    // 删除权限
                    Route::post('delete', [
                        'as' => 'PurviewDelete',
                        'name' => '权限删除',
                        'parent' => 'PurviewIndex',
                        'uses' => 'PurviewController@delete'
                    ]);
                    // 权限填充页
                    Route::get('seed', [
                        'as' => 'PurviewSeed',
                        'name' => '权限填充页',
                        'parent' => 'SystemModule',
                        'uses' => 'PurviewController@getSeed'
                    ]);
                    // 自动扫描admin的routes.php
                    Route::post('seed/routes', [
                        'as' => 'PurviewSeedByRoutes',
                        'name' => '权限路由填充',
                        'parent' => 'PurviewSeed',
                        'uses' => 'PurviewController@seedByRoutes'
                    ]);
                    // 手动添加和sidebar-menu.tpl+header.tpl一样的菜单填充
                    Route::post('seed/menus', [
                        'as' => 'PurviewSeedByMenus',
                        'name' => '权限菜单填充',
                        'parent' => 'PurviewSeed',
                        'uses' => 'PurviewController@seedByMenus'
                    ]);
                });

                // 机构管理
                Route::group([
                    'prefix' => 'org'
                ], function ()
                {
                    // 机构首页
                    Route::get('/', [
                        'as' => 'OrgIndex',
                        'name' => '机构管理',
                        'parent' => 'OrgDeptModule',
                        'uses' => 'OrgController@index'
                    ]);
                    // 机构添加&编辑
                    Route::get('edit', [
                        'as' => 'OrgEdit',
                        'name' => '机构编辑',
                        'parent' => 'OrgIndex',
                        'uses' => 'OrgController@edit'
                    ]);
                    // 获取某机构的下级机构
                    Route::get('sub', [
                        'as' => 'OrgSub',
                        'name' => '获取机构的下级机构',
                        'parent' => 'OrgIndex',
                        'uses' => 'OrgController@subOrgs'
                    ]);
                    // 获取某机构的下级部门
                    Route::get('sub/dept', [
                        'as' => 'OrgSubDepts',
                        'name' => '获取机构的下级部门',
                        'parent' => 'OrgIndex',
                        'uses' => 'OrgController@subDepts'
                    ]);
                    // 保存机构
                    Route::post('save', [
                        'as' => 'OrgSave',
                        'name' => '机构保存',
                        'parent' => 'OrgIndex',
                        'uses' => 'OrgController@save'
                    ]);
                    // 删除机构
                    Route::post('delete', [
                        'as' => 'OrgDelete',
                        'name' => '机构删除',
                        'parent' => 'OrgIndex',
                        'uses' => 'OrgController@delete'
                    ]);
                });

                // 部门管理
                Route::group([
                    'prefix' => 'dept'
                ], function ()
                {
                    // 部门首页
                    Route::get('/', [
                        'as' => 'DeptIndex',
                        'name' => '部门管理',
                        'parent' => 'OrgDeptModule',
                        'uses' => 'DeptController@index'
                    ]);
                    // 部门添加&编辑
                    Route::get('edit', [
                        'as' => 'DeptEdit',
                        'name' => '部门编辑',
                        'parent' => 'DeptIndex',
                        'uses' => 'DeptController@edit'
                    ]);
                    // 保存部门
                    Route::post('save', [
                        'as' => 'DeptSave',
                        'name' => '部门保存',
                        'parent' => 'DeptIndex',
                        'uses' => 'DeptController@save'
                    ]);
                    // 删除部门
                    Route::post('delete', [
                        'as' => 'DeptDelete',
                        'name' => '部门删除',
                        'parent' => 'DeptIndex',
                        'uses' => 'DeptController@delete'
                    ]);
                    // 切换部门状态
                    Route::post('status', [
                        'as' => 'DeptStatusChange',
                        'name' => '部门状态切换',
                        'parent' => 'DeptIndex',
                        'uses' => 'DeptController@postToggleStatus'
                    ]);
                    // 搜索即将指派的用户列表
                    Route::get('search-users', [
                        'as' => 'SearchUsers',
                        'name' => '搜索指派给部门的用户列表',
                        'parent' => 'DeptIndex',
                        'uses' => 'DeptController@searchUser'
                    ]);
                    // 指派用户
                    Route::post('assign-users', [
                        'as' => 'AssignUsers',
                        'name' => '部门用户指派',
                        'parent' => 'DeptIndex',
                        'uses' => 'DeptController@assignUser'
                    ]);
                });

                // 用户管理
                Route::group([
                    'prefix' => 'user'
                ], function ()
                {
                    // 用户首页
                    Route::get('/', [
                        'as' => 'UserIndex',
                        'name' => '用户管理',
                        'parent' => 'CRMModule',
                        'uses' => 'UserController@index'
                    ]);
                    // 用户添加&编辑
                    Route::get('edit', [
                        'as' => 'UserEdit',
                        'name' => '用户编辑',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@edit'
                    ]);
                    // 保存用户
                    Route::post('save', [
                        'as' => 'UserSave',
                        'name' => '用户保存',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@save'
                    ]);
                    // 删除用户
                    Route::post('delete', [
                        'as' => 'UserDelete',
                        'name' => '用户删除',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@delete'
                    ]);
                    // 切换用户状态
                    Route::post('status', [
                        'as' => 'UserStatusChange',
                        'name' => '用户状态切换',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@postToggleStatus'
                    ]);
                    // 用户的上级领导
                    Route::get('parent-user', [
                        'as' => 'UserParentUser',
                        'name' => '获取用户上级领导',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@parentUser'
                    ]);
                    // 指派所巡店的商户
                    Route::get('cust', [
                        'as' => 'UserAssignCust',
                        'name' => '搜索指派给用户的商户列表',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@assignCust'
                    ]);
                    // 保存指派所巡店的商户
                    Route::post('cust', [
                        'as' => 'UserAssignCustSave',
                        'name' => '用户商户指派',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@saveAssignCust'
                    ]);
                    // 搜索上级领导用户列表
                    Route::get('search-users', [
                        'as' => 'SearchParentUsers',
                        'name' => '搜索指派为上级的用户列表',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@searchParentUser'
                    ]);
                    // 指派上级领导
                    Route::post('assign-parent-users', [
                        'as' => 'AssignParentUsers',
                        'name' => '用户上级指派',
                        'parent' => 'UserIndex',
                        'uses' => 'UserController@assignParentUser'
                    ]);
                });

                // 商户管理
                Route::group([
                    'prefix' => 'cust'
                ], function ()
                {
                    // 商户首页
                    Route::get('/', [
                        'as' => 'CustIndex',
                        'name' => '商户管理',
                        'parent' => 'CRMModule',
                        'uses' => 'CustController@index'
                    ]);
                    // 商户添加&编辑
                    Route::get('edit', [
                        'as' => 'CustEdit',
                        'name' => '商户编辑',
                        'parent' => 'CustIndex',
                        'uses' => 'CustController@edit'
                    ]);
                    // 保存商户
                    Route::post('save', [
                        'as' => 'CustSave',
                        'name' => '商户保存',
                        'parent' => 'CustIndex',
                        'uses' => 'CustController@save'
                    ]);
                    // 删除商户
                    Route::post('delete', [
                        'as' => 'CustDelete',
                        'name' => '商户删除',
                        'parent' => 'CustIndex',
                        'uses' => 'CustController@delete'
                    ]);
                    // 搜索巡店者
                    Route::get('search-users', [
                        'as' => 'SearchCustUsers',
                        'name' => '给商户搜索巡店者',
                        'parent' => 'CustIndex',
                        'uses' => 'CustController@searchCustUser'
                    ]);
                    // 指派巡店者
                    Route::post('assign-cust-users', [
                        'as' => 'AssignCustUsers',
                        'name' => '商户巡店者指派',
                        'parent' => 'CustIndex',
                        'uses' => 'CustController@assignCustUser'
                    ]);
                    // 商户等级首页
                    Route::get('level', [
                        'as' => 'CustLevelIndex',
                        'name' => '商户等级管理',
                        'parent' => 'CRMModule',
                        'uses' => 'CustLevelController@index'
                    ]);
                    // 商户等级添加&编辑
                    Route::get('level/edit', [
                        'as' => 'CustLevelEdit',
                        'name' => '商户等级编辑',
                        'parent' => 'CustLevelIndex',
                        'uses' => 'CustLevelController@edit'
                    ]);
                    // 商户等级添加&编辑
                    Route::post('level/save', [
                        'as' => 'CustLevelSave',
                        'name' => '商户等级保存',
                        'parent' => 'CustLevelIndex',
                        'uses' => 'CustLevelController@save'
                    ]);
                    // 删除商户等级
                    Route::post('level/delete', [
                        'as' => 'CustLevelDelete',
                        'name' => '商户等级删除',
                        'parent' => 'CustLevelIndex',
                        'uses' => 'CustLevelController@delete'
                    ]);
                });

                // 意见反馈管理
                Route::group([
                    'prefix' => 'feedback'
                ], function ()
                {
                    // 意见反馈管理
                    Route::get('/', [
                        'as' => 'FeedbackIndex',
                        'name' => '意见反馈管理',
                        'parent' => 'SystemModule',
                        'uses' => 'FeedbackController@index'
                    ]);
                    // 删除意见反馈
                    Route::post('feedback/delete', [
                        'as' => 'FeedbackDelete',
                        'name' => '意见反馈删除',
                        'parent' => 'FeedbackIndex',
                        'uses' => 'FeedbackController@delete'
                    ]);
                });

                // 考勤管理
                Route::group([
                    'prefix' => 'attn'
                ], function ()
                {
                    // 请假类型首页
                    Route::get('leave-type', [
                        'as' => 'LeaveTypeIndex',
                        'name' => '请假类型管理',
                        'parent' => 'LevelModule',
                        'uses' => 'LeaveTypeController@index'
                    ]);
                    // 请假类型添加&编辑
                    Route::get('leave-type/edit', [
                        'as' => 'LeaveTypeEdit',
                        'name' => '请假类型编辑',
                        'parent' => 'LeaveTypeIndex',
                        'uses' => 'LeaveTypeController@edit'
                    ]);
                    // 请假类型保存
                    Route::post('leave-type/save', [
                        'as' => 'LeaveTypeSave',
                        'name' => '请假类型编辑',
                        'parent' => 'LeaveTypeIndex',
                        'uses' => 'LeaveTypeController@save'
                    ]);
                    // 请假类型删除
                    Route::post('leave-type/delete', [
                        'as' => 'LeaveTypeDelete',
                        'name' => '请假类型删除',
                        'parent' => 'LeaveTypeIndex',
                        'uses' => 'LeaveTypeController@delete'
                    ]);
                    // Excel批量导入页
                    Route::get('leave/import', [
                        'as' => 'LeaveExcelIndex',
                        'name' => '请假Excel批量导入页',
                        'parent' => 'LeaveIndex',
                        'uses' => 'LeaveController@excel'
                    ]);
                    // Excel批量导入处理
                    Route::post('leave/import', [
                        'as' => 'LeaveExcelImport',
                        'name' => '请假Excel批量导入处理',
                        'parent' => 'LeaveIndex',
                        'uses' => 'LeaveController@import'
                    ]);
                    // 请假列表
                    Route::get('leave', [
                        'as' => 'LeaveIndex',
                        'name' => '请假管理',
                        'parent' => 'LevelModule',
                        'uses' => 'LeaveController@index'
                    ]);
                    // 修改请假
                    Route::get('leave/edit', [
                        'as' => 'LeaveEdit',
                        'name' => '请假编辑',
                        'parent' => 'LeaveIndex',
                        'uses' => 'LeaveController@edit'
                    ]);
                    // 保存修改
                    Route::post('leave/save', [
                        'as' => 'LeaveSave',
                        'name' => '请假保存',
                        'parent' => 'LeaveIndex',
                        'uses' => 'LeaveController@save'
                    ]);
                    // 删除请假数据
                    Route::post('leave/delete', [
                        'as' => 'LeaveDelete',
                        'name' => '请假删除',
                        'parent' => 'LeaveIndex',
                        'uses' => 'LeaveController@delete'
                    ]);
                    // 加班列表
                    Route::get('overtime', [
                        'as' => 'OvertimeIndex',
                        'name' => '加班管理',
                        'parent' => 'AttnModule',
                        'uses' => 'OvertimeController@index'
                    ]);
                    // 修改加班
                    Route::get('overtime/edit', [
                        'as' => 'OvertimeEdit',
                        'name' => '加班编辑',
                        'parent' => 'OvertimeIndex',
                        'uses' => 'OvertimeController@edit'
                    ]);
                    // 加班保存
                    Route::post('overtime/save', [
                        'as' => 'OvertimeSave',
                        'name' => '加班保存',
                        'parent' => 'OvertimeIndex',
                        'uses' => 'OvertimeController@save'
                    ]);
                    // 删除加班数据
                    Route::post('overtime/delete', [
                        'as' => 'OvertimeDelete',
                        'name' => '加班删除',
                        'parent' => 'OvertimeIndex',
                        'uses' => 'OvertimeController@delete'
                    ]);
                });

                // 任务管理
                Route::group([
                    'prefix' => 'task'
                ], function ()
                {
                    // 常规任务列表
                    Route::get('general', [
                        'as' => 'TaskGeneralIndex',
                        'name' => '常规任务管理',
                        'parent' => 'TaskModule',
                        'uses' => 'TaskGeneralController@index'
                    ]);
                    // 修改常规任务
                    Route::get('general/edit', [
                        'as' => 'TaskGeneralEdit',
                        'name' => '常规任务编辑',
                        'parent' => 'TaskGeneralIndex',
                        'uses' => 'TaskGeneralController@edit'
                    ]);
                    // 常规任务修改
                    Route::post('general/save', [
                        'as' => 'TaskGeneralSave',
                        'name' => '常规任务保存',
                        'parent' => 'TaskGeneralIndex',
                        'uses' => 'TaskGeneralController@save'
                    ]);
                    // 删除常规任务数据
                    Route::post('general/delete', [
                        'as' => 'TaskGeneralDelete',
                        'name' => '常规任务删除',
                        'parent' => 'TaskGeneralIndex',
                        'uses' => 'TaskGeneralController@delete'
                    ]);
                    // 临时任务列表
                    Route::get('assign', [
                        'as' => 'TaskAssignIndex',
                        'name' => '临时任务管理',
                        'parent' => 'TaskModule',
                        'uses' => 'TaskAssignController@index'
                    ]);
                    // 修改临时任务
                    Route::get('assign/edit', [
                        'as' => 'TaskAssignEdit',
                        'name' => '临时任务编辑',
                        'parent' => 'TaskAssignIndex',
                        'uses' => 'TaskAssignController@edit'
                    ]);
                    // 临时任务保存
                    Route::post('assign/save', [
                        'as' => 'TaskAssignSave',
                        'name' => '临时任务保存',
                        'parent' => 'TaskAssignIndex',
                        'uses' => 'TaskAssignController@save'
                    ]);
                    // 删除临时任务数据
                    Route::post('assign/delete', [
                        'as' => 'TaskAssignDelete',
                        'name' => '临时任务删除',
                        'parent' => 'TaskAssignIndex',
                        'uses' => 'TaskAssignController@delete'
                    ]);
                    // 搜索临时任务接收者
                    Route::get('search-users', [
                        'as' => 'SearchAcceptUsers',
                        'name' => '搜索临时任务接收者',
                        'parent' => 'TaskAssignIndex',
                        'uses' => 'TaskAssignController@searchAcceptUser'
                    ]);
                });

                // 文章管理
                Route::group([
                    'prefix' => 'artile'
                ], function ()
                {
                    // 列表页
                    Route::get('/', [
                        'as' => 'ArticleIndex',
                        'name' => '文章管理',
                        'parent' => 'SystemModule',
                        'uses' => 'ArticleController@index'
                    ]);
                    // 添加或编辑文章
                    Route::get('edit', [
                        'as' => 'ArticleEdit',
                        'name' => '文章编辑',
                        'parent' => 'ArticleIndex',
                        'uses' => 'ArticleController@edit'
                    ]);
                    // 保存文章
                    Route::post('save', [
                        'as' => 'ArticleSave',
                        'name' => '文章保存',
                        'parent' => 'ArticleIndex',
                        'uses' => 'ArticleController@save'
                    ]);
                    // 删除文章
                    Route::post('delete', [
                        'as' => 'ArticleDelete',
                        'name' => '文章删除',
                        'parent' => 'ArticleIndex',
                        'uses' => 'ArticleController@delete'
                    ]);
                });
            });
    });


/**
 * @SWG\Info(
 *  title="外勤管理系统接口",
 *  description="易哲慧外勤管理系统",
 *  termsOfServiceUrl = "",
 *  license= "Apache 2.0",
 *  licenseUrl="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 */

// 接口
Route::group([
    'prefix' => 'api',
    'namespace' => 'Api'
],
    function () {
        // 全局模块
        require __DIR__ . '/ApiRoutes/global.php';

        // 用户模块
        require __DIR__ . '/ApiRoutes/user.php';
    }
);
