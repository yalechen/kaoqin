<?php
namespace App\Http\Controllers\Admin;

use App\Models\Dept;
use Validator;
use Input;
use Auth;
use Redirect;

class DeptController extends BaseController
{

    /**
     * 机构管理页
     */
    public function index()
    {
        return v('index');
    }
}