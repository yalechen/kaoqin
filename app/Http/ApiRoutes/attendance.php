<?php


/**
 * @package
 * @category
 *
 * @SWG\Resource(
 *   apiVersion="1.0.0",
 *   swaggerVersion="1.2",
 *   resourcePath="/attendance",
 *   description="Operations about attendance",
 *   @SWG\Produces("application/json")
 * )
 */



/**
 *
 * @SWG\Api(
 *   path="/attendance/list",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="ApiAttendanceList",
 *      summary="月考勤信息",
 *      notes="按月显示考勤信息",
 *      type="TaskGeneral",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="ymonth",
 *              description="年月，格式：2015-07",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          )
 *      ),
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="帐号或密码不正确。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::get('/attendance/list', [
    'as' => 'ApiAttendanceList',
    'middleware' => 'auth',
    'uses' => 'AttendanceController@getAttendanceList'
]);



