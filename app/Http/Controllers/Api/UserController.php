<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;
use Response;
use Input;
use Hash;
use App\Models\User;
use App\Models\Storage;


class UserController extends Controller
{

    /**
     * 登录操作
     *
        登录流程：
        1. 输入手机号，密码，app获取设备号一起传给接口
        2. 接口返回用户信息 last_login_time 为 0000-00-00 00:00:00，则是第一次登陆，app跳出发送验证码页面。
        3. 输入验证码，点击验证按钮，app 再次请求登录，传参数： 账号，密码，短信验证码
        4. 后端验证短信验证码，若通过，则登录有效，将 last_login_time 置为当前时间。
        5. 第二次登录 last_login_time 不为 0000-00-00 00:00:00 ，则直接跳转到登录后的页面
     *  6. 登录成功记录设备号。第二次登录会匹配数据库中设备号，不一致登录失败。设备号字段非空，不再存入设备号。
     *
     */
    public function postLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|mobile|exists:users,mobile',
            'password' => 'required|between:6,12',
            'device_info' => 'required',
        ], [
            'mobile.required' => '手机号不能为空',
            'mobile.mobile' => '手机号格式不正确',
            'mobile.exists' => '手机号不存在',
            'password.required' => '密码不能为空',
            'password.between' => '密码位数必须是在6-16位之间',
            'device_info.required' => '设备号不能为空',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402,$validator->messages()->first());
        }

        $user_status = User::where('mobile',Input::get('mobile'))->pluck('status');
        if($user_status == User::STATUS_OFF){
            // 离职，不能登陆。
            return $this->apiReturn(402,'离职状态，无法登陆');
        }

        $credentials = $request->only('mobile', 'password');

        //第一次登陆标识，用于app 提示：首次登录，修改密码
        $first_login = "N";

        if (Auth::attempt($credentials, $request->has('remember'))) {

            //设备号不一致无法登陆
            if( (Auth::user()->device_info != "") && (Auth::user()->device_info != Input::get('device_info')) ){
                Auth::logout();
                return $this->apiReturn(402,"与第一次登陆的设备不同，无法登陆。若您已换手机，请与管理员联系修改设备信息。");
            }

            //第一次登陆
            if( Auth::user()->last_login_time == "0000-00-00 00:00:00" ){

                $first_login = "Y";

                //有短信验证码
                if(Input::has('vcode')){
                    //检验vcode
                    $validator = Validator::make(Input::all(), [
                        'mobile' => [
                            'required',
                            'mobile'
                        ],
                        'vcode' => [
                            'required',
                            'smsvcode'
                        ]
                    ], [
                        'mobile.required' => '手机号不能为空',
                        'mobile.mobile' => '手机号格式不正确',
                        'vcode.required' => '短信验证码不能为空',
                        'vcode.smsvcode' => '短信验证码不正确'
                    ]);

                    if ($validator->fails()) {
                        return $this->apiReturn(402, $validator->messages()->first());
                    }

                    $user = Auth::user();
                    $user->device_info = Input::get('device_info');
                    $user->last_login_time = date('Y-m-d H:i:s',time());
                    $user->save();
                }

            }else{

                //非第一次登陆
                $user = Auth::user();

                //设备号为空，更新设备号
                if(Auth::user()->device_info == ""){
                    $user->device_info = Input::get('device_info');
                }

                $user->last_login_time = date('Y-m-d H:i:s',time());
                $user->save();
            }

            $info = $this->getUserInfo($first_login);

            return $this->apiReturn(200,"登录成功",$info);

        } else {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402,"密码不正确");
        }


    }


    /**
     * 退出登录
     */
    public function logout()
    {
        Auth::logout();
        return $this->apiReturn(200,"退出成功");
    }

    /**
     * 当前登录状态验证
     */
    public function auth()
    {
        if (Auth::guest()) {
            return $this->apiReturn(401,"未登陆");
        } else {
            return $this->apiReturn(200,"已登录",$this->getUserInfo());
        }
    }


    /**
     * 修改密码保存
     */
    public function postPasswordSave()
    {
        if (Auth::guest()) {
            return $this->apiReturn(401,"请登录后操作");
        }

        $validator = Validator::make(Input::all(), [
            'old_password' => 'required|between:6,12',
            //'password' => 'required|between:6,12|confirmed',
            'password' => 'required|between:6,12',
        ], [
            'old_password.required' => '旧密码不能为空',
            'old_password.between' => '旧密码位数必须是在6-16位之间',
            'password.required' => '新密码不能为空',
            'password.between' => '新密码位数必须是在6-16位之间',
           // 'password.confirmed' => '确认密码与新密码不一致',
        ]);

        if ($validator->fails()) {
            return $this->apiReturn(402,$validator->messages()->first());
        }

        // 验证旧密码是否正确
        if (!Hash::check(Input::get('old_password'), Auth::user()->password)) {

            return $this->apiReturn(402,"旧密码不正确");
        }

        // 修改密码为新的密码
        $user = Auth::user();
        $user->password = Input::get('password');
        $user->save();

        Auth::logout();

        return $this->apiReturn(200,"密码修改成功,请重新登陆");

    }


    /**
     * 重置密码
     */
    public function resetPassword()
    {
        // 验证手机验证码。
        $validator = Validator::make(Input::all(), [
            'mobile' => [
                'required',
                'exists:users,mobile'
            ],
            /*'smsvcode' => [
                'required',
                'smsvcode:mobile,final'
            ],*/
            'password' => [
                'required',
                'between:6,16'
            ]
        ], [
            'mobile.required' => '手机号不能为空',
            'mobile.exists' => '手机号不存在',
            /* 'smsvcode.required' => '短信验证码不能为空',
             'smsvcode.smsvcode' => '短信验证码错误',*/
            'password.required' => '密码不能为空',
            'password.between' => '密码位数必须是在6-16位之间'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        // 取得该用户。
        $user = User::where('mobile', Input::get('mobile'))->first();

        $user->password = Input::get('password');
        $user->save();

        // 强制所有设备退出登录。
        Auth::logout();

        // 返回成功信息。
        return $this->apiReturn(200,"密码重置成功,请重新登陆");
    }

    /**
     * 编辑信息
     */
    public function edit()
    {
        // 取得允许修改的属性列表输入值。
        $inputs = Input::only('avatar_pic_id', 'realmobile');

        // 验证输入。
        $validator = Validator::make($inputs, [
            'avatar_pic_id' => [
                'exists:storages,id'
            ]
        ], [
            'avatar_id.exists' => '头像不存在',
            'sex.in' => '性别必须是在男或女',
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        // 检查头像是否为图片。
        if (! is_null($inputs['avatar_pic_id'])) {
            $file = Storage::find($inputs['avatar_pic_id']);
            if (is_null($file)) {
                $inputs['avatar_pic_id'] = null;
            } elseif (substr($file->mime, 0, 6) != 'image/') {
                return $this->apiReturn(402,"不支持的图片格式");
            }
        }

        // 取得当前登录用户。
        $users = Auth::user();

        // 修改用户属性。
        foreach ($inputs as $key => $value) {
            if (! is_null($value)) {
                $users->$key = $value;
            }
        }


        // 保存修改。
        $users->save();

        return $this->apiReturn(200,"修改信息成功",User::find($users->id));
    }


    /*
     * 获取用户信息
     */
    public function getUserInfo( $first_login = 'N'){
        //返回用户信息
        $user_info = Auth::user();

        //用户角色
        $role = "";
        $roles = $user_info->roles;
        if(! is_null($roles)){
            foreach($roles as $item){
                $role .= $item->name." ";
            }
        }

        $info['mobile'] = $user_info->mobile;
        $info['realname'] = $user_info->realname;
        $info['gender'] = $user_info->gender;
        $info['avatar'] = $user_info->avatar_path;
        $info['birthday'] = $user_info->birthday;
        $info['number'] = $user_info->number;
        $info['department'] = $user_info->dept ? $user_info->dept->name : "";
        $info['role'] = $role;
        $info['province'] = $user_info->province ? $user_info->province->name : "";
        $info['city'] = $user_info->city ? $user_info->city->name : "";
        $info['address'] = $user_info->address ;
        $info['status'] = $user_info->status ;
        $info['device_info'] = $user_info->device_info;
        $info['first_login'] = $first_login;
        $info['last_login_time'] = $user_info->last_login_time;

        return $info;
    }

}
