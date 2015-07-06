<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Input;
use Illuminate\Support\Facades\Lang;

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
        $this->subject=Lang::get('passwords.subject');
        // $this->middleware('guest');
        //PasswordBroker::
    }
}
