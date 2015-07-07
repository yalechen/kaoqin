<?php
namespace App\Http\Controllers\Admin;

use App\Models\CustLevel;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;

class CustLevelController extends BaseController
{

    /**
     * 商户等级管理页
     */
    public function index()
    {
        // 所有等级
        $data = CustLevel::paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = CustLevel::find(Input::get('id'));
        }

        return v('edit', compact('data'));
    }

    /**
     * 保存
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'name' => 'required|unique:cust_level,name,' . Input::get('id'),
            'times' => 'required|integer|min:1',
            'sort' => 'required|integer|min:1'
        ], [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
            'times.required' => '拜访次数不能为空',
            'times.integer' => '拜访次数只能为整数',
            'times.min' => '拜访次数至少要有一次',
            'sort.required' => '排序值不能为空',
            'sort.integer' => '排序值只能为整数',
            'sort.min' => '排序值必须要大于等于1'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $cust_level = CustLevel::findOrNew(Input::get('id'));
        $cust_level->name = trim(Input::get('name'));
        $cust_level->times = Input::get('times');
        $cust_level->sort = Input::get('sort');
        $cust_level->save();

        return Redirect::route("CustLevelIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除商户等级
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:cust_level,id'
        ], [
            'id.required' => '所选商户等级不能为空',
            'id.exists' => '所选商户等级不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("CustLevelIndex")->withMessageError($validator->messages()
                ->all());
        }

        $cust_level = CustLevel::find(Input::get('id'));
        $cust_level->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}