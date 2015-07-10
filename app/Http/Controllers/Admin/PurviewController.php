<?php
namespace App\Http\Controllers\Admin;

use App\Models\Purview;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use Illuminate\Support\Facades\Route;
use Exception;

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
            'id' => 'exists:purviews',
            'name' => 'required',
            'key' => 'required|min:3|max:50|alpha_dash|unique:purviews,key,' . Input::get('id'),
            'controller' => 'required_if:type,' . Purview::TYPE_ACTION,
            'action' => 'required_if:type,' . Purview::TYPE_ACTION,
            'type' => 'required|in:' . Purview::TYPE_ACTION . ',' . Purview::TYPE_MENU,
            'useon' => 'required|in:' . Purview::USEON_PC . ',' . Purview::USEON_APP,
            'status' => 'required|in:' . Purview::STATUS_ON . ',' . Purview::STATUS_OFF,
            'sort' => 'integer'
        ], [
            'id.exists' => '修改的权限不存在',
            'name.required' => '权限名称不能为空',
            'key.required' => '标识key不能为空',
            'key.alpha_dash' => '标识key仅允许字母、数字、破折号（-）以及底线（_）',
            'key.unique' => '标识key已存在',
            'key.min' => '标识key不能少于3个字符',
            'key.max' => '标识key不能多余30个字符',
            'controller.required_if' => '当类型为功能方法时，控制器不能为空',
            'action.required_if' => '当类型为功能方法时，方法不能为空',
            'type.required' => '权限类型不能为空',
            'type.in' => '权限类型仅能为功能方法或菜单',
            'useon.required' => '使用平台不能为空',
            'useon.in' => '使用平台仅能为PC或APP',
            'status.required' => '状态不能为空',
            'status.in' => '状态仅能为开启和关闭'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        // 获取已经有相同的路由名的路由规则
        $have_key = Purview::whereKey(Input::get('key'));
        // 防止上级指派为自己
        if (! is_null($have_key->first())) {
            if ($have_key->first()->id == Input::get('parent_id')) {
                return Response::make('亲，上级权限不能指派成自己哦', 402);
            }
        }

        Input::has('id') && $have_key->where('id', '!=', Input::get('id'));
        $have_key = $have_key->get();

        // 如果有相同的路由名，则附加条件必须不能相同
        $query = [];
        if (! $have_key->isEmpty()) {
            if (Input::has('condition')) {
                parse_str(Input::get('condition'), $query);
                count(array_filter($query)) < 1 && $query = [
                    Input::get('condition')
                ];
            }
            foreach ($have_key as $key) {
                if (empty($query)) {
                    if (empty($key->condition)) {
                        return Response::make('已经有相同的权限规则了！', 402);
                    }
                } else {
                    $diff = array_diff_assoc($query, $key->condition);
                    if (empty($diff)) {
                        return Response::make('已经有相同的权限规则了！', 402);
                    }
                }
            }
        }

        try {
            $purview = Purview::findOrNew(Input::get('id', 0));
            $purview->name = Input::get('name');
            $purview->key = Input::get('key');
            $purview->parent_id = Input::get('parent_id');
            $purview->controller = Input::get('controller');
            $purview->action = Input::get('action');
            $purview->type = Input::get('type');
            $purview->useon = Input::get('useon');
            $purview->condition = Input::get('condition', '');
            $purview->status = Input::get('status');
            $purview->sort = Input::get('sort', 100);
            $purview->save();
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 402);
        }

        return $purview;
    }

    /**
     * 删除权限
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:purviews,id'
        ], [
            'id.required' => '所选权限不能为空',
            'id.exists' => '所选权限不存在'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        // 查看此权限是否被指派到角色中了
        $temp = Purview::find(Input::get('id'))->roles()->get();
        if (! $temp->isEmpty()) {
            return Response::make('此权限已经被指派到角色，不能被删除', 402);
        }
        Purview::find(Input::get('id'))->delete();
        return 'success';
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
            'id' => 'required|exists:purviews,id'
        ]);

        if ($validator->fails()) {
            return Validator::make($validator->messages()->first(), 402);
        }

        $purview_info = Purview::find(Input::get('id'))->toArray();
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
            foreach ($list as $item) {
                $html .= <<<HTML
<span data-id='{$item['id']}' class='node' style='margin:10px 40px 10px 40px; font-size:13px;display: inline-block;cursor: pointer;' data-toggle='popover'>{$item['name']}</span>
HTML;
            }
        } else {
            foreach ($list as $item) {
                $level = count(array_filter(explode(':', $item['path']))) - 1;
                $style = "";
                if (empty($level)) {
                    $style = "border:1px solid #EBEBEB;";
                }
                $sub_html = '';
                if (! empty($item['sub_node'])) {
                    $sub_html = $this->returnTreeView($item['sub_node']);
                }
                $html .= <<<HTML
<section class="panel"><header class="panel-heading" data-id='{$item['id']}'><span data-id='{$item['id']}' class='node' data-toggle='popover'>{$item['name']}</span></header><div class="panel-body"><div style="margin-left: 10px;margin-top: 0px;{$style}">{$sub_html}</div></div></section>
HTML;
            }
        }
        return $html;
    }
}