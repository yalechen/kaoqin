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

// 后台管理
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function ()
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

    // 上传文件
    Route::post('file', [
        'as' => 'FileUpload',
        'uses' => 'StorageController@postFile'
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
    ], function ()
    {
        // 后台首页
        Route::get('/', [
            'as' => 'AdminIndex',
            'uses' => 'IndexController@index'
        ]);

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
        });

        // 用户管理
        Route::group([
            'prefix' => 'user'
        ], function ()
        {
            // 部门首页
            Route::get('/', [
                'as' => 'UserIndex',
                'uses' => 'UserController@index'
            ]);
            // 部门添加&编辑
            Route::get('edit', [
                'as' => 'UserEdit',
                'uses' => 'UserController@edit'
            ]);
            // 保存部门
            Route::post('save', [
                'as' => 'UserSave',
                'uses' => 'UserController@save'
            ]);
            // 删除部门
            Route::post('delete', [
                'as' => 'UserDelete',
                'uses' => 'UserController@delete'
            ]);
            // 切换用户状态
            Route::post('status', [
                'as' => 'UserStatusChange',
                'uses' => 'UserController@postToggleStatus'
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
        });
    });
});