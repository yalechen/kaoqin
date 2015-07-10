<?php
namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Purview;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use Illuminate\Support\Facades\Route;

class PurviewController extends BaseController
{

    /**
     * 权限管理页
     */
    public function index()
    {
        $list = Purview::all()->toArray();
        $list = array_sort($list, function ($value)
        {
            return $value['path'];
        });
        $tree_node = $this->returnTreeNode($list);
        $html = $this->returnTreeView($tree_node);
        return v('index')->with(compact('list', 'html'));
    }

    /**
     * 新增&编辑权限
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = Purview::find(Input::get('id'));
        }

        return v('edit', compact('data'));
    }

    /**
     * 保存权限
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'key' => 'required|min:3|max:30|alpha_dash|unique:roles,key,' . Input::get('id'),
            'name' => 'required|min:3|max:30',
            'status' => 'required|in:' . Role::STATUS_OFF . ',' . Role::STATUS_ON
        ], [
            'key.required' => '标识key不能为空',
            'key.alpha_dash' => '标识key仅允许字母、数字、破折号（-）以及底线（_）',
            'key.unique' => '标识key已存在',
            'key.min' => '标识key不能少于3个字符',
            'key.max' => '标识key不能多余30个字符',
            'name.required' => '角色名称不能为空',
            'name.min' => '角色名称必须最少要有3个字符',
            'name.max' => '角色名称不能大于30个字符',
            'status.required' => '状态不能为空',
            'status.in' => '状态仅能为开启和关闭'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }
        // 获取Logo图片信息
        $id = Input::has('id') ? Input::get('id') : 0;
        $role = Role::findOrNew(Input::get('id'));
        $role->key = Input::get('key');
        $role->name = trim(Input::get('name'));
        $role->remark = Input::get('remark', '');
        $role->status = Input::get('status');
        $role->save();

        return Redirect::route("RoleIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除角色
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:roles,id'
        ], [
            'id.required' => '所选角色不能为空',
            'id.exists' => '所选角色不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("RoleIndex")->withMessageError($validator->messages()
                ->all());
        }

        // 查看是否有指派了权限或者角色
        $role = Role::find(Input::get('id'));
        if (empty($role->users) !== true) {
            return Redirect::route("RoleIndex")->withMessageError('角色已经被指派了用户，请先取消指派，在进行删除');
        }
        if (empty($role->purviews) !== true) {
            return Redirect::route("RoleIndex")->withMessageError('角色已经被指派了权限，请先取消指派，在进行删除');
        }

        $role->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 权限填充
     */
    public function getSeed()
    {
        // 一级菜单
        $menus = Purview::whereParentPath('')->whereType(Purview::TYPE_MENU)
            ->whereStatus(Purview::STATUS_ON)
            ->get();

        return v('seed')->withMenus($menus);
    }

    /**
     * 获取权限菜单的子菜单
     */
    public function subMenu()
    {
        $menus = array();
        $parent_id = Input::get('parent_id');
        if ($parent_id > 0) {
            $menus = Purview::find(Input::get('parent_id'))->childNode()
                ->whereType(Purview::TYPE_MENU)
                ->whereStatus(Purview::STATUS_ON)
                ->get();
        }
        return Response::json($menus);
    }

    /**
     * 填充菜单权限表
     */
    public function seedByMenus()
    {
        $validator = Validator::make(Input::all(), [
            'name' => 'required',
            'key' => 'required|unique:purviews,key',
            'path' => 'required',
            'useon' => 'required|in:' . Purview::USEON_PC . ',' . Purview::USEON_APP,
            'status' => 'required|in:' . Purview::STATUS_ON . ',' . Purview::STATUS_OFF,
            'sort' => 'integer'
        ], [
            'status.required' => '权限状态不能为空',
            'status.in' => '权限状态仅能为开启或者关闭',
            'sort.integer' => '排序值只能为整数',
            'useon.required' => '应用平台不能为空',
            'useon.in' => '应用平台仅能为PC或者APP',
            'path.required' => '上级菜单不能为空',
            'key.required' => '标识key不能为空',
            'key.unique' => '标识key已经存在',
            'name.required' => '名称不能为空'
        ]);

        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $path = array_filter(Input::get('path'));
        // 保存菜单权限
        $purview = new Purview();
        $purview->name = Input::get('name');
        $purview->key = Input::get('key');
        $purview->type = Purview::TYPE_MENU;
        $purview->status = Input::get('status');
        $purview->useon = Input::get('useon');
        $purview->sort = Input::get('sort', 100);
        $purview->parent_id = (int) end($path);
        $purview->save();

        // 填充完成，回到权限列表页
        return Redirect::route('PurviewSeed')->withMessageSuccess(Input::get('name') . '菜单权限添加成功');
    }

    /**
     * 从路由填充权限
     */
    public function seedByRoutes()
    {
        // 需要过滤的内容
        $remove = [
            'debugbar.openhandler',
            'debugbar.assets.css',
            'debugbar.assets.js',
            null
        ];

        // 遍历Admin的routes.php填充到权限表
        // 注意：route的写法请保持一定要有name
        $success = $error = 0;
        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();
            $key = $route->getName();
            if (! in_array($key, $remove)) {
                // $prefix = $route->getPrefix();
                $middleware = array_get($action, 'middleware');
                if ($middleware == 'auth.admin') {
                    $name = array_get($action, 'name') === null ? '' : array_get($action, 'name');
                    $controller = substr($route->getActionName(), 0, strpos($route->getActionName(), '@'));
                    // 如果key已经存在则不进行填充
                    $temp = Purview::whereKey($key)->first();
                    if (is_null($temp)) {
                        $success = $success + 1;
                        echo $key . '<br>';
                        $purview = new Purview();
                        $purview->name = $name;
                        $purview->key = $key;
                        $purview->controller = $controller;
                        $purview->action = str_replace($controller . '@', '', $route->getActionName());
                        $purview->type = Purview::TYPE_ACTION;
                        $purview->status = Purview::STATUS_ON;
                        $purview->useon = Purview::USEON_PC;
                        $purview->save();
                    } else {
                        $error = $error + 1;
                    }
                }
            }
        }

        // 更新上级
        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();
            $key = $route->getName();
            if (! in_array($key, $remove)) {
                // $prefix = $route->getPrefix();
                $middleware = array_get($action, 'middleware');
                if ($middleware == 'auth.admin') {
                    $name = array_get($action, 'name') === null ? '' : array_get($action, 'name');
                    $controller = substr($route->getActionName(), 0, strpos($route->getActionName(), '@'));
                    $purview = Purview::whereKey($key)->first();
                    if (! empty($action['parent'])) {
                        $old = Purview::whereKey($action['parent'])->first();
                        if (! is_null($old)) {
                            $purview->parent_id = $old->id;
                            $purview->save();
                        }
                    }
                }
            }
        }

        // 填充完成，回到权限列表页
        return Redirect::route('PurviewSeed')->withMessageSuccess('遍历后台路由成功，权限已填充完成，成功' . $success . '笔，失败' . $error . '笔(已经存在)');
    }

    /**
     * 获取权限详情
     */
    public function info()
    {
        $validator = Validator::make(Input::all(), [
            'id' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            return Validator::make($validator->messages()->first(), 402);
        }

        $purview_info = Purview::find(Input::get('id'))->toArray();
        if (! empty($purview_info['condition']) && current(array_keys($purview_info['condition'])) == 0) {
            $purview_info['condition'] = current($purview_info['condition']);
        } else {
            $purview_info['condition'] = http_build_query($purview_info['condition']);
        }
        return $purview_info;
    }

    /**
     * 递归生成层级形式的权限列表
     */
    protected function returnTreeNode($list, $index = 0)
    {
        $tree_nodes = [];
        foreach ($list as $node) {
            if ($node['parent_id'] == $index) {
                $node['sub_node'] = $this->returnTreeNode($list, $node['id']);
                $tree_nodes[$node['id']] = $node;
            }
        }
        return array_sort($tree_nodes, function ($value)
        {
            return $value['sort'];
        });
    }

    /**
     * 生成层级形式页面
     */
    protected function returnTreeView($list)
    {
        $html = "";
        $sub = array_filter(array_fetch($list, 'sub_node'));
        if (empty($sub)) {
            //$html .= '<div style="margin-left: 10px;margin-top: 0px;">';
            foreach ($list as $item) {
                $html .= <<<HTML
<span data-id='{$item['id']}' class='node' style='width: 220px; margin:10px 10px 10px 0px; font-size:13px;display: inline-block;cursor: pointer;' data-toggle='popover' data-placement='top'>{$item['name']}</span>
HTML;
            }
            //$html .= '</div>';
        } else {
            foreach ($list as $item) {
                $level = count(array_filter(explode(':', $item['path']))) - 1;
                $style = "";
                //$span_style = "width: 150px;";
                if (empty($level)) {
                    $style = "border:1px solid #EBEBEB;";
                    //$span_style = "background:#ECECEC;font-weight:bolder;padding:5px 15px;";
                }
                $sub_html = '';
                if (! empty($item['sub_node'])) {
                    $sub_html = $this->returnTreeView($item['sub_node']);
                }
                $html .= <<<HTML
<section class="panel"><header class="panel-heading" data-id='{$item['id']}'><span data-id='{$item['id']}' class='node' data-toggle='popover' data-placement='top'>{$item['name']}</span></header><div class="panel-body"><div style="margin-left: 10px;margin-top: 0px;{$style}">{$sub_html}</div></div></section>
HTML;
            }
        }
        return $html;
    }
}