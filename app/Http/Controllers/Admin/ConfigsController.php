<?php
namespace App\Http\Controllers\Admin;

use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;

class ConfigsController extends BaseController
{

    /**
     * 设置配置
     */
    public function index()
    {
        $data = config('base_info');

        return v('index', compact('data'));
    }

    /**
     * 保存配置信息
     */
    public function save()
    {
        $validator = Validator::make(Input::all(), [
            'logo' => 'required',
            'favicon' => 'required',
            'sms_sn' => 'required',
            'sms_pwd' => 'required',
            'sms_signature' => 'required'
        ], [
            'logo.required' => '系统Logo路径不能为空',
            'favicon.required' => '系统图标路径不能为空',
            'sms_sn.required' => '短信通道SN不能为空',
            'sms_pwd.required' => '短信通道密码不能为空',
            'sms_signature.required' => '短信签名不能为空'
        ]);

        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $setup = config('base_info');
        $setup['logo'] = trim(Input::get('logo'));
        $setup['favicon'] = trim(Input::get('favicon'));
        $setup['sms_sn'] = trim(Input::get('sms_sn'));
        $setup['sms_pwd'] = Input::get('sms_pwd', '');
        $setup['sms_signature'] = trim(Input::get('sms_signature'));
        $setup['sms_pretend'] = Input::get('sms_pretend', '');

        file_put_contents(config_path('base_info.php'), '<?php return ' . var_export($setup, true) . '; ');

        return Redirect::route("ConfigsIndex")->withMessageSuccess('修改成功');
    }
}