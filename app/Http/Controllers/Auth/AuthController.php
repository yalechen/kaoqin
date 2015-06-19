<?php
namespace App\Http\Controllers\Auth;

use Auth;
use Redirect;
use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => 'getLogout'
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister()
    {
        return User::create([
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => bcrypt(Input::get('password'))
        ]);
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), array(
            'email' => 'required|email',
            'password' => 'required'
        ));
        if ($validator->fails()) {
            return redirect()->back()->with('message_error', $validator->messages()
                ->first());
            ;
        }
        // 登录验证。
        if (! Auth::attempt([
            'email' => Input::get('email'),
            'password' => Input::get('password')
        ], Input::has('remember'))) {
            return Redirect::back()->withErrors([
                '用户名与密码不匹配。'
            ]);
        }

        // 登录成功
        return Redirect::route('/');
    }

    public function getLogout()
    {
        // 退出系统
        Auth::logout();
        return Redirect::route('/');
    }
}
