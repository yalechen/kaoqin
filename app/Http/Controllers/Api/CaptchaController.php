<?php namespace App\Http\Controllers\Api;

/**
 * 验证码控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;
use Response;
use Input;
use Cache;
use App\Models\Member;


class CaptchaController extends Controller
{

    /**
     * 获取短信验证码
     *
     * @param string $mobile
     */
    public function getSmsVcode()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), array(
            'mobile' => array(
                'required',
                'regex:/^1[3-8][0-9]{9}$/'
            )
        ), array(
            'mobile.required' => '手机号不能为空',
            'mobile.regex' => '手机号格式不正确'
        ));
        if ($validator->fails()) {
            return $this->apiReturn(402, $validator->messages()->first());
        }

        if(Input::has('checkMobileExist')){
            $rs =  Member::where('phone',Input::get('mobile'))->first();
            if(Input::get('checkMobileExist') == 'Y' && is_null($rs) ) {
                //不存在，报错
                return $this->apiReturn(402, '手机号不存在');
            }

            if(Input::get('checkMobileExist') == 'N' && ! is_null($rs) ){
                //存在，报错
                return $this->apiReturn(402, '手机号已存在');
            }
        }

        // 调用短信验证码宏，发送短信验证码。
        return $this->sendSmsVcode(Input::get('mobile'));
    }


    /**
     * 发送邮箱验证码
     *
     * @param string $email
     */
    public function getEmailVcode()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), array(
            'email' => array(
                'required',
                'email'
            )
        ), array(
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确'
        ));
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }
        // 调用短信验证码宏，发送短信验证码。
        return Response::emailvcode(Input::get('email'));
    }

    /**
     * 邮箱验证码的验证规则
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param Validator $validator
     */
    public function validateEmailVcode($attribute, $value, $parameters, $validator)
    {
        // 取出要验证的手机号。
        $email = array_get($validator->getData(), isset($parameters[0]) ? $parameters[0] : 'email');

        // 计算缓存的key。
        $key = 'email_captcha/' . $email;

        // 取出验证码，并与输入进行比较。
        $email_vcode = Cache::get($key);
        if (trim($value) == $email_vcode['vcode']) {
            // 删除缓存。
            if (! empty($parameters[1]) && $parameters[1] == 'final') {
                Cache::forget($key);
            }
            return true;
        }
        return false;
    }


    /**
     * 验证短信码
     *
     * @author jois
     */
    public function checkSmsVcode()
    {
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

        return $this->apiReturn(200, '验证成功');
    }


    /**
     * 短信验证码的验证规则
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param Validator $validator
     */
    public function validateSmsVcode($attribute, $value, $parameters, $validator)
    {
        // 取出要验证的手机号。
        $mobile = array_get($validator->getData(), isset($parameters[0]) ? $parameters[0] : 'mobile');

        // 计算缓存的key。
        $key = 'captcha/' . $mobile;

        // 取出验证码，并与输入进行比较。
        $sms = Cache::get($key);

        if ($value == $sms['vcode']) {
            // 删除缓存。
            Cache::forget($key);
            /*if (! empty($parameters[1]) && $parameters[1] == 'final') {
                Cache::forget($key);
            }*/
            return true;
        }
        return false;
    }
}
