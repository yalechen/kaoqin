<?php
namespace App\Http\Controllers\Admin;

use App\Models\Org;
use Validator;
use Input;
use Auth;
use Redirect;

class OrgController extends BaseController
{

    /**
     * 机构管理页
     */
    public function index()
    {
        // 获取第一级机构
        $org = Org::whereParentPath('')->get();

        // 根据参数获取机构信息
        $data = Org::orderBy('sort', 'desc')->paginate(15);
        if (Input::has('org_id')) {
            $org_ids = array_filter(Input::get('org_id'));
            $word = end($org_ids);
            if ($word) {
                $data = Org::where('parent_path', 'like', "%:{$word}:%")->orWhere('id', $word)
                    ->orderBy('sort', 'desc')
                    ->paginate(15);
            }
        }

        // 返回视图
        return v('index')->with(compact('data', 'org'));
    }
}