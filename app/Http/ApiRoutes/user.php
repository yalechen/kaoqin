<?php


/**
 * @package
 * @category
 *
 * @SWG\Resource(
 *   apiVersion="1.0.0",
 *   swaggerVersion="1.2",
 *   resourcePath="/user",
 *   description="Operations about user",
 *   @SWG\Produces("application/json")
 * )
 */


/**
 *
 * @SWG\Api(
 *   path="/login",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="UserLogin",
 *      summary="登录",
 *      notes="登录系统，获取系统登录状态会话。",
 *      type="Staff",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="mobile",
 *              description="手机号",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="password",
 *              description="密码",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="device_info",
 *              description="设备号(IMEI)",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="vcode",
 *              description="首次登录，需短信验证码",
 *              paramType="query",
 *              required=false,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="remember_me",
 *              description="记住我",
 *              paramType="query",
 *              required=false,
 *              type="boolean",
 *              defaultValue="false"
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="帐号或密码不正确。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::post('login', [
    'as' => 'UserLogin',
    'uses' => 'UserController@postLogin'
]);

/**
 *
 * @SWG\Api(
 *   path="/logout",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="UserLogout",
 *      summary="退出",
 *      notes="注销掉当前会话，并将登录状态从所有设备中移除。",
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=401, message="当前未登录。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::post('logout', [
    'as' => 'UserLogout',
    'before'=>'auth',
    'uses' => 'UserController@logout'
]);


/**
 *
 * @SWG\Api(
 *   path="/auth",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="UserAuth",
 *      summary="检查状态",
 *      notes="检查当前Cookie的登录状态有效性。",
 *      type="Staff",
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=401, message="登录状态无效。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::get('auth', [
    'as' => 'UserAuth',
    'uses' => 'UserController@auth'
]);

/**
 *
 * @SWG\Api(
 *   path="/user/info",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="UserInfo",
 *      summary="获取信息",
 *      notes="根据user_id获取用户的详细信息。",
 *      type="Staff",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="user_id",
 *              description="用户ID",
 *              paramType="query",
 *              required=true,
 *              type="integer"
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="表单验证失败。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::get('user/info', [
    'as' => 'UserInfo',
    'before'=>'auth',
    'uses' => 'UserController@info'
]);


/**
 * @SWG\Api(
 *  path="/user/password",
 *  @SWG\Operation(
 *      method="POST",
 *      nickname="ModifyPassword",
 *      summary="通过旧密码修改到新密码",
 *      notes="登录状态下，通过旧密码修改到新密码。",
 *      type="Staff",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="old_password",
 *              description="原始密码",
 *              type="string",
 *              required=true,
 *              paramType="query"
 *          ),
 *          @SWG\Parameter(
 *              name="password",
 *              description="新的密码",
 *              type="string",
 *              required=true,
 *              paramType="query"
 *          )
 *      ),
 *      @SWG\ResponseMessage(code=402, message="提交参数错误提示"),
 *      @SWG\ResponseMessage(code=200, message="success")
 *  ),
 * )
 */
Route::post('user/password', [
    'before' => 'auth',
    'as' => 'ModifyPassword',
    'uses' => 'UserController@postPasswordSave'
]);


/**
 * @SWG\Api(
 *  path="/user/reset_password",
 *  @SWG\Operation(
 *      method="POST",
 *      summary="用户找回（重置）密码",
 *      notes="用户找回（重置）密码。",
 *      type="string",
 *      nickname="resetPassword",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="mobile",
 *              type="string",
 *              description="用户绑定的手机号码",
 *              required=true,
 *              paramType="query"
 *          ),
 *          @SWG\Parameter(
 *              name="password",
 *              type="string",
 *              description="用户的新密码",
 *              required=true,
 *              paramType="query"
 *          )
 *      ),
 *      @SWG\ResponseMessage(code=402, message="提交参数错误提示"),
 *      @SWG\ResponseMessage(code=200, message="success")
 *  )
 * )
 */
Route::post('user/reset_password', [
    'as' => 'resetPassword',
    'uses' => 'UserController@resetPassword'
]);


/**
 *
 * @SWG\Api(
 *   path="/user/edit",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="UserEdit",
 *      summary="编辑信息",
 *      notes="编辑当前登录用户的个人信息。不传的字段为不修改。",
 *      type="Staff",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="avatar_pic_id",
 *              description="头像图片id",
 *              paramType="query",
 *              type="integer"
 *          ),
 *          @SWG\Parameter(
 *              name="realname",
 *              description="真实姓名",
 *              type="string",
 *              paramType="query"
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=401, message="当前未登录。"),
 *          @SWG\ResponseMessage(code=402, message="表单验证失败。"),
 *          @SWG\ResponseMessage(code=200, message="用户模型。")
 *      )
 *   )
 * )
 */
Route::post('user/edit', [
    'as' => 'UserEdit',
    'uses' => 'UserController@edit'
]);
