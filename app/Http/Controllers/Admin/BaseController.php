<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request as I;

class BaseController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
}