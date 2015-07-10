<?php


/**
 * @package
 * @category
 *
 * @SWG\Resource(
 *   apiVersion="1.0.0",
 *   swaggerVersion="1.2",
 *   resourcePath="/task",
 *   description="Operations about task",
 *   @SWG\Produces("application/json")
 * )
 */


/**
 *
 * @SWG\Api(
 *   path="/task/sign",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="ApiTaskSign",
 *      summary="上下班打卡",
 *      notes="上下班打卡",
 *      type="TaskSign",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="type",
 *              description="类型[On|Off]，上班：On下班：Off",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="lng",
 *              description="经度",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="lat",
 *              description="纬度",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="location",
 *              description="位置",
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
Route::post('/task/sign', [
    'as' => 'ApiTaskSign',
    'middleware' => 'auth',
    'uses' => 'TaskController@postTaskSign'
]);


/**
 *
 * @SWG\Api(
 *   path="/task/assign_cust_list",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="ApiAssignCustList",
 *      summary="巡查门店列表",
 *      notes="巡查门店列表,按照后台设定范围搜索周边门店，可进行搜索",
 *      type="TaskGeneral",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="lat",
 *              description="经度",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="lng",
 *              description="纬度",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="name",
 *              description="门店名称搜索,搜索员工负责门店，不限制位置",
 *              paramType="query",
 *              required=false,
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
Route::get('/task/assign_cust_list', [
    'as' => 'ApiAssignCustList',
    'middleware' => 'auth',
    'uses' => 'TaskController@getAssignCustList'
]);


/**
 *
 * @SWG\Api(
 *   path="/task/assign_cust_sign",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="ApiAssignCustSign",
 *      summary="巡查门店签到",
 *      notes="巡查门店签到",
 *      type="TaskGeneral",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="cust_id",
 *              description="客户id（门店id）",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="lat",
 *              description="经度",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="lng",
 *              description="纬度",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="location",
 *              description="位置",
 *              paramType="query",
 *              required=true,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="mileage",
 *              description="里程(单位：公里)，距上次打卡之间的里程",
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
Route::post('/task/assign_cust_sign', [
    'as' => 'ApiAssignCustSign',
    'middleware' => 'auth',
    'uses' => 'TaskController@postAssignCustSign'
]);
