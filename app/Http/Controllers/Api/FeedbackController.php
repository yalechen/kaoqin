<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;
use Response;
use Input;
use Hash;
use App\Models\Feedback;


class FeedbackController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 添加反馈
     */
    public function postAddFeedback(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ], [
            'content.required' => '标题不能为空',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        $feedback =  new Feedback();

        $feedback->content = Input::get('content');
        $feedback->user_id = Auth::user()->id;
        $feedback->save();

        return $this->apiReturn(200, '添加反馈成功', $feedback);

    }

}
