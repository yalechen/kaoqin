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
 *   path="/task/cust_list",
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
 *              description="门店名称搜索（搜索员工负责门店，不限制位置）",
 *              paramType="query",
 *              required=false,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="level_id",
 *              description="门店等级搜索（搜索员工负责门店，不限制位置）",
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
Route::get('/task/cust_list', [
    'as' => 'ApiAssignCustList',
    'middleware' => 'auth',
    'uses' => 'TaskController@getAssignCustList'
]);


/**
 *
 * @SWG\Api(
 *   path="/task/cust_sign",
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
Route::post('/task/cust_sign', [
    'as' => 'ApiAssignCustSign',
    'middleware' => 'auth',
    'uses' => 'TaskController@postAssignCustSign'
]);


/**
 *
 * @SWG\Api(
 *   path="/task/log_list",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="ApiTaskLogList",
 *      summary="外勤日志列表",
 *      notes="用于外勤日志列表，里程统计页面",
 *      type="TaskGeneral",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="from_date",
 *              description="开始日期",
 *              paramType="query",
 *              required=false,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="to_date",
 *              description="结束日期",
 *              paramType="query",
 *              required=false,
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="limit",
 *              description="每页显示数量",
 *              paramType="query",
 *              type="string"
 *          ),
 *          @SWG\Parameter(
 *              name="page",
 *              description="页码",
 *              paramType="query",
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
Route::get('/task/log_list', [
    'as' => 'ApiTaskLogList',
    'middleware' => 'auth',
    'uses' => 'TaskController@getTaskLogList'
]);

/**
 *
 * @SWG\Api(
 *   path="/task/log_detail",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="ApiTaskLogDetail",
 *      summary="外勤日志详情",
 *      notes="外勤日志详情",
 *      type="TaskGeneral",
 *      @SWG\Parameters(
 *          @SWG\Parameter(
 *              name="visit_date",
 *              description="日期",
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
Route::get('/task/log_detail', [
    'as' => 'ApiTaskLogDetail',
    'middleware' => 'auth',
    'uses' => 'TaskController@getTaskLogDetail'
]);


/**
 *
 * @SWG\Api(
 *   path="/task/index",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="ApiTaskLogIndex",
 *      summary="外勤日志首页",
 *      notes="外勤日志首页",
 *      type="TaskGeneral",
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="帐号或密码不正确。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::get('/task/index', [
    'as' => 'ApiTaskLogIndex',
    'middleware' => 'auth',
    'uses' => 'TaskController@getTaskLogIndex'
]);


/**
 *
 * @SWG\Api(
 *   path="/task/visit",
 *   @SWG\Operation(
 *      method="GET",
 *      nickname="ApiTaskVisit",
 *      summary="我的巡店任务",
 *      notes="每月固定的巡店任务",
 *      type="TaskGeneral",
 *      @SWG\ResponseMessages(
 *          @SWG\ResponseMessage(code=402, message="帐号或密码不正确。"),
 *          @SWG\ResponseMessage(code=200, message="成功。")
 *      )
 *   )
 * )
 */
Route::get('/task/visit', [
    'as' => 'ApiTaskVisit',
    'middleware' => 'auth',
    'uses' => 'TaskController@getTaskVisit'
]);

