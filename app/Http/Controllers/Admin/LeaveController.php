<?php
namespace App\Http\Controllers\Admin;

use App\Models\LeaveType;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;

class LeaveController extends BaseController
{

    /**
     * 请假批量导入页
     */
    public function index()
    {
        // 返回视图
        return v('index');
    }

    /**
     * 请假批量导入处理
     */
    public function import()
    {
        if (! Input::hasFile('file')) {
            return Redirect::route('GetFakeData')->with('import_res', '请先选择文件后在进行提交！');
        }
        $num = $error_num = $total = 0;
        $error_arr = [];

        Excel::selectSheetsByIndex(0)->filter('chunk')
            ->load(Input::file('file'))
            ->chunk(100, function ($results) use(&$num, &$error_num, &$error_arr, &$total)
        {
            // 批量写入数据库
            foreach ($results->toArray() as $row) {
                if (empty($row)) {
                    continue;
                }
                $inputs['username'] = (string) trim($row[1]);
                $inputs['mobile'] = (string) trim($row[2]);
                $inputs['nickname'] = (string) trim($row[3]);
                $inputs['password'] = (string) trim($row[4]);

                if (empty($inputs['username']) || $inputs['username'] == '用户名') {
                    continue;
                }
                ++ $total;

                // 验证
                $validator = Validator::make($inputs, [
                    'username' => 'required|alpha_dash|unique:members,username',
                    'mobile' => 'required|mobile|unique:members,mobile',
                    'nickname' => 'required',
                    'password' => 'required'
                ], [
                    'username.required' => '用户名不能为空',
                    'username.alpha_dash' => '用户名只能是数字、字母、下划线的字符组合（3-20位内）',
                    'username.unique' => '用户名已经被注册了',
                    'mobile.unique' => '手机号已经存在',
                    'mobile.required' => '手机号不能为空',
                    'mobile.mobile' => '手机号格式错误',
                    'nickname.required' => '昵称不能为空',
                    'password.required' => '密码不能为空'
                ]);

                // 记录错误信息
                if ($validator->fails()) {
                    ++ $error_num;
                    $error_arr[$total] = [
                        'username' => $inputs['username'],
                        'mobile' => $inputs['mobile'],
                        'message' => $validator->messages()
                            ->first()
                    ];
                    continue;
                }

                // 注册
                $user = new Member();
                $user->username = $inputs['username'];
                $user->password = $inputs['password'];
                $user->mobile = $inputs['mobile'];
                $user->nickname = $inputs['nickname'];
                $user->is_fake = Member::IS_FAKE_YES;
                $user->save();

                $info = new MemberInfo();
                $info->member()
                    ->associate($user);
                $info->save();
                ++ $num;
            }
        });

        return Redirect::route('GetFakeData')->with('import_error_message', $error_arr)->with('import_res', "上传成功，共{$total}笔记录，成功导入{$num}个用户，失败{$error_num}笔！");
    }
}