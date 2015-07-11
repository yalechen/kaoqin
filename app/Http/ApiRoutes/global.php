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
 *   path="/captcha/send_sms_vcode",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="SendSMSVcode",
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
Route::GET('captcha/send_sms_vcode', [
    'as' => 'ApiCaptchaSMS',
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
Route::POST('captcha/check_sms_vcode', [
    'as' => 'ApiCheckSmsVcode',
    'uses' => 'CaptchaController@checkSmsVcode'
]);



/**
 *
 * @SWG\Api(
 *   path="/feedback/add",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="PostAddFeedback",
 *      summary="添加反馈",
 *      notes="添加意见反馈",
 *      type="Feedback",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="content",
 *              description="内容",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="表单验证失败。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::post('/feedback/add', [
    'as' => 'PostAddFeedback',
    'middleware'=>'auth',
    'uses' => 'FeedbackController@postAddFeedback'
]);







