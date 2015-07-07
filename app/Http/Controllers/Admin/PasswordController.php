<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Input;
use Illuminate\Support\Facades\Lang;
use Redirect;
use App\Models\PasswordResets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordController extends BaseController
{
    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->subject = Lang::get('passwords.subject');
        // $this->middleware('guest');
        // PasswordBroker::
    }

    /**
     * 重置密码页
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            return Redirect::route("AdminGetLogin")->withMessageError('token参数为空');
        }
        if (is_null(PasswordResets::whereToken($token)->first())) {
            return Redirect::route("AdminGetLogin")->withMessageError('token已失效');
        }

        return view('auth.reset')->with('token', $token);
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $response = Password::reset($credentials, function ($user, $password)
        {
            $this->resetPassword($user, $password);
        });
        switch ($response) {
            case Password::PASSWORD_RESET:
                // 重置成功
                return Redirect::route("AdminGetLogin")->withInput($request->only('email'))
                    ->withMessageSuccess('密码重置成功');

            default:
                return Redirect::route("AdminGetLogin")->withInput($request->only('email'))
                    ->withErrors([
                    'email' => trans($response)
                ]);
        }
    }
}
