<?php

class Sms
{

    protected $sn;

    protected $pwd;

    protected $signature;

    protected $pretend;

    /**
     * Send sms operating.
     *
     * @param string $moblie
     * @param string $content
     * @return boolean
     */
    public function __construct()
    {
        $this->sn = config('base_info.sms_sn');
        $this->pwd = config('base_info.sms_pwd');
        $this->signature = config('base_info.sms_signature');
        $this->pretend = config('base_info.sms_pretend');
    }

    function send($moblie, $content)
    {
        if (! $this->pretend) {
            if (is_array($moblie)) {
                $moblie = implode(', ', $moblie);
            }
            $pwd = strtoupper(md5($this->sn . $this->pwd));
            $data = array(
                'sn' => $this->sn, // 提供的账号
                'pwd' => $pwd, // 此处密码需要加密 加密方式为 md5(sn+password) 32位大写
                'mobile' => $moblie, // 手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
                'content' => $content . $this->signature, // 短信内容
                'ext' => '',
                'stime' => '', // 定时时间 格式为2011-6-29 11:09:21
                'rrid' => '', // 默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
                'msgfmt' => ''
            );
            $url = "http://sdk.entinfo.cn:8061/webservice.asmx/mdsmssend?";

            $retult = $this->api_notice_increment($url, $data);

            preg_match('/<string.*?>(.*?)<\/string>/i', $retult, $str);
            $result = explode('-', $str[1]);

            return count($result) > 1 ? false : true;
        }
        return 1;
    }

    /**
     * Call sms api.
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    function api_notice_increment($url, $data)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
                                               // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
                                               // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
                                               // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        $data = http_build_query($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $lst = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl); // 捕抓异常
        }
        curl_close($curl);
        return $lst;
    }
}
