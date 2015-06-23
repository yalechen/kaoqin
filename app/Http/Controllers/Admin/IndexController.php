<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Validator;
use Input;
use Auth;
use Redirect;

class IndexController extends BaseController
{

    /**
     * 管理后台主页
     */
    public function index()
    {
        return v('index');
    }

    /**
     * 登录界面
     */
    public function getLogin()
    {
        return v('login');
    }

    /**
     * 登录处理
     */
    public function postLogin()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), array(
            'username' => [
                'required'
            ],
            'password' => [
                'required',
                'between:6,16'
            ],
            'remember_me' => [
                'in:true,false'
            ]
        ));
        if ($validator->fails()) {
            return redirect()->back()->with('message_error', $validator->messages()
                ->first());
        }

        // 登录验证。
        if (! Auth::attempt([
            'name' => Input::get('username'),
            'password' => Input::get('password')
        ], Input::get('remember_me', 'false') == 'true')) {
            return Redirect::back()->with('message_error', '用户名与密码不匹配。');
        }

        // 验证是否为超级管理员角色
        if (! in_array('super_admin', Auth::user()->roles->lists('key')->all())) {
            // 退出登录
            Auth::logout();
            return Redirect::back()->with('message_error', '登录失败，您并非超级管理员。');
        }

        // 登录成功
        return Redirect::intended('admin');
    }

    /**
     * 退出登录状态
     */
    public function logout()
    {
        // 退出系统
        Auth::logout();
        return Redirect::route('AdminGetLogin');
    }
}