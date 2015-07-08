<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Validator;
use Cache;
use Response;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->extendValidator();
    }

    /**
     * 扩展验证器
     */
    protected function extendValidator()
    {
        /**
         * 手机短信验证码验证
         *
         * @param string $attribute
         * @param string $value
         * @param array $parameters
         * @param Validator $validator
         */
        Validator::extend('smsvcode', function ($attribute, $value, $parameters, $validator)
        {
            // 取出要验证的手机号。
            $mobile = array_get($validator->getData(), isset($parameters[0]) ? $parameters[0] : 'mobile');

            // 计算缓存的key。
            $key = 'captcha/' . $mobile;

            // 取出验证码，并与输入进行比较。
            $sms = Cache::get($key);

            if ($value == $sms['vcode']) {
                // 删除缓存。
                if (! empty($parameters[1]) && $parameters[1] == 'final') {
                    Cache::forget($key);
                }
                return true;
            }
            return false;
        });

        /**
         * 邮箱验证码验证
         *
         * @param string $attribute
         * @param string $value
         * @param array $parameters
         * @param Validator $validator
         */
        Validator::extend('emailvcode', function ($attribute, $value, $parameters, $validator)
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
        });

        // 手机号码验证器
        Validator::extend('mobile', function ($attribute, $value, $parameters)
        {
            if (preg_match('/^1[34578][\d]{9}$/', $value)) {
                return true;
            }
            return false;
        });

        // 验证图片。
        Validator::extend('user_file_mime', function ($attribute, $value, $parameters)
        {
            $pids = (array) $value;
            if ($pids) {
                $pictures = UserFile::whereIn('id', $pids)->get();
                if (count($pictures) != count($pids)) {
                    return false;
                }
                foreach ($pictures as $picture) {
                    if (! preg_match($parameters[0], $picture->storage->mime)) {
                        return false;
                    }
                }
            }
            return true;
        });

        // 验证身份证号码 http://blog.sina.com.cn/s/blog_8c68af43010170rv.html
        Validator::extend('id_number', function ($attribute, $value, $parameters)
        {
            // 15位身份证
            if (preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/', $value)) {
                return true;
            }
            // 18位身份证
            if (preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x|X)$/', $value)) {
                return true;
            }
            return false;
        });

        // 验证真实姓名
        Validator::extend('realname', function ($attribute, $value, $parameters)
        {
            if (preg_match('/^[\x{4e00}-\x{9fa5}]{2,4}$/u', $value)) {
                return true;
            }
            return false;
        });

        include app_path() . '/Services/sms/sms.php';

        // 注册短信验证码宏。
        Response::macro('smsvcode', function ($mobile)
        {
            // 短信验证码数据不使用数据库存储而存放在缓存中。
            // 定义一个key。
            $key = 'captcha/' . $mobile;

            // 检查是否在60秒内进行了重复获取。
            if (Cache::has($key) && array_get(Cache::get($key), 'expired') > time()) {
                return Response::make('不能在60内重复获取。', 403);
            }

            // 生成一个4位的数字验证码。
            $vcode = sprintf('%04d', mt_rand(0, 9999));

            // 如果不是单元测试环境，发送短信到手机。
            if (! \App::runningUnitTests()) {
                $a = with(new \Sms())->send($mobile, '您本次操作的验证码为：' . $vcode . '，该验证码10分钟内有效。');
            }

            // 将验证码写入缓存中，并设置10分钟的过期时间，1分钟可重设。
            Cache::put($key, [
                'vcode' => $vcode,
                'expired' => time() + 60
            ], 10);

            // 返回成功信息。
            return 'success';
        });
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
            if (! empty($parameters[1]) && $parameters[1] == 'final') {
                Cache::forget($key);
            }
            return true;
        }
        return false;
    }


    /*
     * 发送短信验证码
     */
    public function sendSmsVcode($mobile){
        // 短信验证码数据不使用数据库存储而存放在缓存中。
        // 定义一个key。
        $key = 'captcha/' . $mobile;

        // 检查是否在60秒内进行了重复获取。
        if (Cache::has($key) && array_get(Cache::get($key), 'expired') > time()) {
            return $this->apiReturn(402, '不能在60内重复获取');
        }

        // 生成一个4位的数字验证码。
        $vcode = sprintf('%04d', mt_rand(0, 9999));

        tpl_send_sms(2,$mobile, "#company#=外勤系统&#code#=".$vcode);


        // 将验证码写入缓存中，并设置10分钟的过期时间，1分钟可重设。
        Cache::put($key, [
            'vcode' => $vcode,
            'expired' => time() + 60
        ], 10);

        // 返回成功信息。
        return $this->apiReturn(200, '短信验证码发送成功');
    }

    /*
     * api数据返回
     */

    public function apiReturn($code = "", $msg = "", $data = "", $count = "")
    {

        $rtn = array(
            "code" => $code,
            "msg" => $msg,
        );

        //data 有值且对对象
        if ($data && is_object($data)) {
            //对象转为数组不空，则返回data
            if($data->toArray()){
                $rtn = array_merge($rtn, array("data" => $data));
            }
        }elseif($data){
            //如果直接是有值得数组，返回，[]不会进入这里
            $rtn = array_merge($rtn, array("data" => $data));
        }

        if ($count) {
            $rtn = array_merge($rtn, array("count" => $count));
        }

        return $rtn;
    }
}
