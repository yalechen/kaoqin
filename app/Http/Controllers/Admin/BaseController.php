<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request as I;
use App\Models\City;
use App\Models\District;

class BaseController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取某省份下城市列表
     */
    public function getCity()
    {
        return City::whereProvinceId(I::get("province_id", '0'))->get();
    }

    /**
     * 获取某城市下区县列表
     */
    public function getDistrict()
    {
        return District::whereCityId(I::get("city_id", '0'))->get();
    }
}