<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Validator;
use Input;
use Auth;
use Redirect;
use Response as R;
use App\Events\UserLastLoginTimeSeek;

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
        $validator = Validator::make(Input::all(), [
            'username' => 'required|min:3|max:30',
            'password' => 'required|between:6,16',
            'remember_me' => 'in:true,false'
        ], [
            'username.required' => '用户名不能为空',
            'username.min' => '用户名请填写3-30个字符或汉字',
            'username.max' => '用户名请填写3-30个字符或汉字',
            'password.required' => '密码不能为空',
            'password.between' => '密码请填写6-16个字符'
        ]);
        if ($validator->fails()) {
            return R::make($validator->messages()->first(), 402);
        }

        // 登录验证。
        if (! Auth::attempt([
            'name' => Input::get('username'),
            'password' => Input::get('password')
        ], Input::get('remember_me', 'false') == 'true')) {
            return R::make('用户名与密码不匹配。', 402);
        }

        // 验证是否为超级管理员角色
        if (! in_array('super_admin', Auth::user()->roles->lists('key')->all())) {
            // 退出登录
            Auth::logout();
            return R::make('登录失败，您并非超级管理员。', 402);
        }

        $user = Auth::user();

        // 填充登录时间
        event(new UserLastLoginTimeSeek($user));

        // 登录成功
        return $user;
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