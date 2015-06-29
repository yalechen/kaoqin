<?php
namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;

class FeedbackController extends BaseController
{

    /**
     * 意见反馈管理页
     */
    public function index()
    {
        // 所有意见
        $data = Feedback::with('user')->paginate(15);
        if (Input::has('key')) {
            $key = Input::get('key');
            $data = Feedback::with('user')->where('content', 'like', "%{$key}%")->paginate(15);
        }

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 删除意见反馈
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:feedbacks,id'
        ], [
            'id.required' => '所选意见反馈不能为空',
            'id.exists' => '所选意见反馈不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("FeedbackIndex")->withMessageError($validator->messages()
                ->all());
        }

        $feedback = Feedback::find(Input::get('id'));
        $feedback->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}