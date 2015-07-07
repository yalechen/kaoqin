<?php

/**
 * @SWG\Resource(
 *  resourcePath="/global",
 *  description="系统全局"
 * )
 */

/**
 *
 * @SWG\Api(
 *   path="/file",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="FileUpload",
 *      summary="上传文件",
 *      notes="在所有接口中使用到文件ID，都由此接口得到。（注意使用文件上传方式 enctype=&quot;multipart/form-data&quot; ）",
 *      type="Storage",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="file",
 *              description="文件",
 *              paramType="form",
 *              required=true,
 *              type="file"
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=401, message="尚未登录。"),
 *          @SWG\ResponseMessage(code=402, message="表单验证失败。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */

Route::post('file', [
    'as' => 'FileUpload',
   // 'before' => 'auth',
    'uses' => 'StorageController@postFile'
]);

/**
 *
 * @SWG\Api(
 *   path="/file",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="FilePull",
 *      summary="获取文件",
 *      notes="根据文件ID取得指定文件。如果文件是图片，支持获取指定宽高的缩略图。",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="id",
 *              description="文件ID",
 *              paramType="query",
 *              required=true,
 *              type="integer"
 *          ),
 *          @SWG\Parameter(
 *              name="width",
 *              description="宽度",
 *              paramType="query",
 *              type="integer"
 *          ),
 *          @SWG\Parameter(
 *              name="height",
 *              description="高度",
 *              paramType="query",
 *              type="integer"
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=404, message="文件不存在。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::get('file', [
    'as' => 'FilePull',
    'uses' => 'StorageController@getFile'
]);


/**
 *
 * @SWG\Api(
 *   path="/captcha/send_sms_vcode",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="CaptchaSMSVcode",
 *      summary="获取短信验证码",
 *      notes="所有需要提供短信验证码的地方，都是通过此接口获取。",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="mobile",
 *              description="手机号码",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="checkMobileExist",
 *              description="验证手机号是否存在。例如：注册需验证手机号不存在，需选择N",
 *              paramType="query",
 *              required=false,
 *              type="string",
 *              enum="['','Y','N']",
 *              defaultValue=""
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="表单验证失败。"),
 *          @SWG\ResponseMessage(code=403, message="不能在60内重复获取。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::post('captcha/send_sms_vcode', [
    'as' => 'CaptchaSMS',
    'uses' => 'CaptchaController@getSmsVcode'
]);


/**
 * @SWG\Api(
 *  path="/captcha/check_sms_vcode",
 *  @SWG\Operation(
 *      method="POST",
 *      summary="手机短信码验证",
 *      notes="提供短信验证码验证。10分钟内有效,验证成功后不能再使用",
 *      type="string",
 *      nickname="CheckSmsVcode",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="mobile",
 *              type="string",
 *              description="手机号码",
 *              required=true,
 *              paramType="query"
 *          ),
 *          @SWG\Parameter(
 *              name="vcode",
 *              type="string",
 *              description="短信验证码",
 *              required=true,
 *              paramType="query"
 *          )
 *      ),
 *      @SWG\ResponseMessage(code=402, message="提交参数错误提示"),
 *      @SWG\ResponseMessage(code=200, message="success")
 *  )
 * )
 */
Route::post('captcha/check_sms_vcode', [
    'as' => 'CheckSmsVcode',
    'uses' => 'CaptchaController@checkSmsVcode'
]);






