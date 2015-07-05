<?php
Route::get('/', function ()
{
    return view('welcome');
});

Route::get('register', [
    'as' => 'GetRegister',
    'uses' => 'Auth\AuthController@getRegister'
]);

Route::post('register', [
    'as' => 'PostRegister',
    'uses' => 'Auth\AuthController@postRegister'
]);

Route::get('login', [
    'as' => 'GetLogin',
    'uses' => 'Auth\AuthController@getLogin'
]);

Route::post('login', [
    'as' => 'PostLogin',
    'uses' => 'Auth\AuthController@postLogin'
]);

Route::get('Logout', [
    'as' => 'Logout',
    'uses' => 'Auth\AuthController@getLogout'
]);

// ckeditor 上传文件
Route::post('ck-file', [
    'as' => 'CKFileUpload',
    'uses' => 'CkEditorController@postFile'
]);
// 通用上传文件
Route::post('upload-file', [
    'as' => 'UploadFile',
    'uses' => 'StorageController@postFile'
]);
// 表单上传文件
Route::post('form-upload-file', [
    'as' => 'FormUploadFile',
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
            'uses' => 'BaseController@getCity'
        ]);

        // 获取区县列表
        Route::get('district', [
            'as' => 'DistrictPull',
            'uses' => 'BaseController@getDistrict'
        ]);

        // 退出
        Route::get('logout', [
            'as' => 'AdminLogout',
            'uses' => 'IndexController@logout'
        ]);
        // 登录
        Route::get('login', [
            'as' => 'AdminGetLogin',
            'uses' => 'IndexController@getLogin'
        ]);
        // 登录
        Route::post('login', [
            'as' => 'AdminPostLogin',
            'uses' => 'IndexController@postLogin'
        ]);

        Route::group([
            'middleware' => 'auth.admin'
        ],
            function ()
            {
                // 后台首页
                Route::get('/', [
                    'as' => 'AdminIndex',
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
                        'uses' => 'ConfigsController@index'
                    ]);
                    // 保存设置
                    Route::post('/', [
                        'as' => 'ConfigsSave',
                        'uses' => 'ConfigsController@save'
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
                        'uses' => 'OrgController@index'
                    ]);
                    // 机构添加&编辑
                    Route::get('edit', [
                        'as' => 'OrgEdit',
                        'uses' => 'OrgController@edit'
                    ]);
                    // 获取某机构的下级机构
                    Route::get('sub', [
                        'as' => 'OrgSub',
                        'uses' => 'OrgController@subOrgs'
                    ]);
                    // 获取某机构的下级部门
                    Route::get('sub/dept', [
                        'as' => 'OrgSubDepts',
                        'uses' => 'OrgController@subDepts'
                    ]);
                    // 保存机构
                    Route::post('save', [
                        'as' => 'OrgSave',
                        'uses' => 'OrgController@save'
                    ]);
                    // 删除机构
                    Route::post('delete', [
                        'as' => 'OrgDelete',
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
                        'uses' => 'DeptController@index'
                    ]);
                    // 部门添加&编辑
                    Route::get('edit', [
                        'as' => 'DeptEdit',
                        'uses' => 'DeptController@edit'
                    ]);
                    // 保存部门
                    Route::post('save', [
                        'as' => 'DeptSave',
                        'uses' => 'DeptController@save'
                    ]);
                    // 删除部门
                    Route::post('delete', [
                        'as' => 'DeptDelete',
                        'uses' => 'DeptController@delete'
                    ]);
                    // 切换部门状态
                    Route::post('status', [
                        'as' => 'DeptStatusChange',
                        'uses' => 'DeptController@postToggleStatus'
                    ]);
                    // 搜索即将指派的用户列表
                    Route::get('search-users', [
                        'as' => 'SearchUsers',
                        'uses' => 'DeptController@searchUser'
                    ]);
                    // 指派用户
                    Route::post('assign-users', [
                        'as' => 'AssignUsers',
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
                        'uses' => 'UserController@index'
                    ]);
                    // 用户添加&编辑
                    Route::get('edit', [
                        'as' => 'UserEdit',
                        'uses' => 'UserController@edit'
                    ]);
                    // 保存用户
                    Route::post('save', [
                        'as' => 'UserSave',
                        'uses' => 'UserController@save'
                    ]);
                    // 删除用户
                    Route::post('delete', [
                        'as' => 'UserDelete',
                        'uses' => 'UserController@delete'
                    ]);
                    // 切换用户状态
                    Route::post('status', [
                        'as' => 'UserStatusChange',
                        'uses' => 'UserController@postToggleStatus'
                    ]);
                    // 用户的上级领导
                    Route::get('parent-user', [
                        'as' => 'UserParentUser',
                        'uses' => 'UserController@parentUser'
                    ]);

                    // 指派所巡店的商户
                    Route::get('cust', [
                        'as' => 'UserAssignCust',
                        'uses' => 'UserController@assignCust'
                    ]);
                    // 保存指派所巡店的商户
                    Route::post('cust', [
                        'as' => 'UserAssignCustSave',
                        'uses' => 'UserController@saveAssignCust'
                    ]);
                    // 搜索上级领导用户列表
                    Route::get('search-users', [
                        'as' => 'SearchParentUsers',
                        'uses' => 'UserController@searchParentUser'
                    ]);
                    // 指派上级领导
                    Route::post('assign-parent-users', [
                        'as' => 'AssignParentUsers',
                        'uses' => 'UserController@assignParentUser'
                    ]);
                    // 保存头像
                    Route::post('avatar', [
                        'as' => 'UserAvatarSave',
                        'uses' => 'UserController@avatarSave'
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
                        'uses' => 'CustController@index'
                    ]);
                    // 商户添加&编辑
                    Route::get('edit', [
                        'as' => 'CustEdit',
                        'uses' => 'CustController@edit'
                    ]);
                    // 保存商户
                    Route::post('save', [
                        'as' => 'CustSave',
                        'uses' => 'CustController@save'
                    ]);
                    // 删除商户
                    Route::post('delete', [
                        'as' => 'CustDelete',
                        'uses' => 'CustController@delete'
                    ]);
                    // 搜索巡店者
                    Route::get('search-users', [
                        'as' => 'SearchCustUsers',
                        'uses' => 'CustController@searchCustUser'
                    ]);
                    // 指派巡店者
                    Route::post('assign-cust-users', [
                        'as' => 'AssignCustUsers',
                        'uses' => 'CustController@assignCustUser'
                    ]);

                    // 商户等级首页
                    Route::get('level', [
                        'as' => 'CustLevelIndex',
                        'uses' => 'CustLevelController@index'
                    ]);
                    // 商户等级添加&编辑
                    Route::get('level/edit', [
                        'as' => 'CustLevelEdit',
                        'uses' => 'CustLevelController@edit'
                    ]);
                    // 商户等级添加&编辑
                    Route::post('level/save', [
                        'as' => 'CustLevelSave',
                        'uses' => 'CustLevelController@save'
                    ]);
                    // 删除商户等级
                    Route::post('level/delete', [
                        'as' => 'CustLevelDelete',
                        'uses' => 'CustLevelController@delete'
                    ]);

                    // 意见反馈管理
                    Route::get('feedback', [
                        'as' => 'FeedbackIndex',
                        'uses' => 'FeedbackController@index'
                    ]);
                    // 删除意见反馈
                    Route::post('feedback/delete', [
                        'as' => 'FeedbackDelete',
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
                        'uses' => 'LeaveTypeController@index'
                    ]);
                    // 请假类型添加&编辑
                    Route::get('leave-type/edit', [
                        'as' => 'LeaveTypeEdit',
                        'uses' => 'LeaveTypeController@edit'
                    ]);
                    // 请假类型添加&编辑
                    Route::post('leave-type/save', [
                        'as' => 'LeaveTypeSave',
                        'uses' => 'LeaveTypeController@save'
                    ]);
                    // 删除请假类型
                    Route::post('leave-type/delete', [
                        'as' => 'LeaveTypeDelete',
                        'uses' => 'LeaveTypeController@delete'
                    ]);
                    // Excel批量导入页
                    Route::get('leave/import', [
                        'as' => 'LeaveExcelIndex',
                        'uses' => 'LeaveController@excel'
                    ]);
                    // Excel批量导入处理
                    Route::post('leave/import', [
                        'as' => 'LeaveExcelImport',
                        'uses' => 'LeaveController@import'
                    ]);
                    // 请假列表
                    Route::get('leave', [
                        'as' => 'LeaveIndex',
                        'uses' => 'LeaveController@index'
                    ]);
                    // 修改请假
                    Route::get('leave/edit', [
                        'as' => 'LeaveEdit',
                        'uses' => 'LeaveController@edit'
                    ]);
                    // 保存修改
                    Route::post('leave/save', [
                        'as' => 'LeaveSave',
                        'uses' => 'LeaveController@save'
                    ]);
                    // 删除请假数据
                    Route::post('leave/delete', [
                        'as' => 'LeaveDelete',
                        'uses' => 'LeaveController@delete'
                    ]);

                    // 加班列表
                    Route::get('overtime', [
                        'as' => 'OvertimeIndex',
                        'uses' => 'OvertimeController@index'
                    ]);
                    // 修改请假
                    Route::get('overtime/edit', [
                        'as' => 'OvertimeEdit',
                        'uses' => 'OvertimeController@edit'
                    ]);
                    // 加班修改
                    Route::post('overtime/save', [
                        'as' => 'OvertimeSave',
                        'uses' => 'OvertimeController@save'
                    ]);
                    // 删除加班数据
                    Route::post('overtime/delete', [
                        'as' => 'OvertimeDelete',
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
                        'uses' => 'TaskGeneralController@index'
                    ]);
                    // 修改常规任务
                    Route::get('general/edit', [
                        'as' => 'TaskGeneralEdit',
                        'uses' => 'TaskGeneralController@edit'
                    ]);
                    // 常规任务修改
                    Route::post('general/save', [
                        'as' => 'TaskGeneralSave',
                        'uses' => 'TaskGeneralController@save'
                    ]);
                    // 删除常规任务数据
                    Route::post('general/delete', [
                        'as' => 'TaskGeneralDelete',
                        'uses' => 'TaskGeneralController@delete'
                    ]);

                    // 临时任务列表
                    Route::get('assign', [
                        'as' => 'TaskAssignIndex',
                        'uses' => 'TaskAssignController@index'
                    ]);
                    // 修改临时任务
                    Route::get('assign/edit', [
                        'as' => 'TaskAssignEdit',
                        'uses' => 'TaskAssignController@edit'
                    ]);
                    // 常规临时修改
                    Route::post('assign/save', [
                        'as' => 'TaskAssignSave',
                        'uses' => 'TaskAssignController@save'
                    ]);
                    // 删除临时任务数据
                    Route::post('assign/delete', [
                        'as' => 'TaskAssignDelete',
                        'uses' => 'TaskAssignController@delete'
                    ]);
                    // 搜索临时任务接收者
                    Route::get('search-users', [
                        'as' => 'SearchAcceptUsers',
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
                        'uses' => 'ArticleController@index'
                    ]);
                    // 添加或编辑文章
                    Route::get('edit', [
                        'as' => 'ArticleEdit',
                        'uses' => 'ArticleController@edit'
                    ]);
                    // 保存文章
                    Route::post('save', [
                        'as' => 'ArticleSave',
                        'uses' => 'ArticleController@save'
                    ]);
                    // 删除文章
                    Route::post('delete', [
                        'as' => 'ArticleDelete',
                        'uses' => 'ArticleController@delete'
                    ]);
                });
            });
    });