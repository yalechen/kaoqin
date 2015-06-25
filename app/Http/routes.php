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
            Route::get('edit/{id?}', [
                'as' => 'OrgEdit',
                'uses' => 'OrgController@edit'
            ]);
        });

        // 部门管理
        Route::group([
            'prefix' => 'dept'
        ], function ()
        {
            Route::get('/', [
                'as' => 'DeptIndex',
                'uses' => 'DeptController@index'
            ]);
        });
    });
});