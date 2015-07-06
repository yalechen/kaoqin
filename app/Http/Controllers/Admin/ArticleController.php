<?php
namespace App\Http\Controllers\Admin;

use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use App\Models\Article;

class ArticleController extends BaseController
{

    /**
     * 文章管理页
     */
    public function index()
    {
        // 排序
        if (Input::has('sort')) {
            $data = Article::orderBy('sort', Input::get('sort'));
        } else {
            $data = Article::orderBy('sort', 'desc');
        }
        // 根据参数获取文章信息
        if (Input::has('key')) {
            $data->where('title', 'like', '%' . Input::get('key') . '%');
        }
        if (Input::has('type')) {
            $data->whereType(Input::get('type'));
        }

        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑文章
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = Article::find(Input::input('id'));
        }

        return v('edit', compact('data'));
    }

    /**
     * 保存
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'title' => 'required|unique:articles,title,' . Input::get('id'),
            'type' => 'required|in:' . Article::TYPE_ABOUT . ',' . Article::TYPE_ACTION,
            'content' => 'required',
            'sort' => 'required|integer|min:1'
        ], [
            'title.required' => '标题不能为空',
            'title.unique' => '标题已经存在了',
            'type.required' => '类型不能为空',
            'type.in' => '类型只能为"关于我们"和"功能介绍"',
            'content.required' => '内容不能为空',
            'sort.required' => '排序值不能为空',
            'sort.integer' => '排序值只能为整数',
            'sort.min' => '排序值必须要大于等于1'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }
        // 获取Logo图片信息
        $id = Input::has('id') ? Input::get('id') : 0;
        $article = Article::findOrNew(Input::get('id'));
        $article->title = trim(Input::get('title'));
        $article->content = trim(Input::get('content'));
        $article->type = Input::get('type');
        $article->user()->associate(Auth::user());
        $article->sort = Input::get('sort');
        $article->save();

        return Redirect::route("ArticleIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除文章
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:articles,id'
        ], [
            'id.required' => '所选文章不能为空',
            'id.exists' => '所选文章不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("ArticleIndex")->withMessageError($validator->messages()
                ->all());
        }

        $article = Article::find(Input::get('id'));
        $article->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}