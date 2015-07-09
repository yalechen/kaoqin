<?php

/**
 * @SWG\Resource(
 *  resourcePath="/file",
 *  description="文件管理"
 * )
 */

/**
 *
 * @SWG\Api(
 *   path="/file",
 *   @SWG\Operation(
 *      method="POST",
 *      nickname="ApiFileUpload",
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
    'as' => 'ApiFileUpload',
    'middleware' => 'auth',
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
 *              name="hash",
 *              description="文件hash",
 *              paramType="query",
 *              required=true,
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
    'as' => 'ApiFilePull',
    'uses' => 'StorageController@getFile'
]);






