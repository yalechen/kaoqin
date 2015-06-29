<?php  namespace App\Http\Controllers;

use Input;

class CkEditorController extends StorageController
{


    public function __construct()
    {
        parent::__construct('upload');
    }



    /**
     * 上传文件
     */
    public function postFile()
    {
        $userfile = parent::postFile();
        // 文件大小超过php.ini 设置的大小，文件是不会被上传的，即报如下错误
        if($userfile == "error"){
            return '<script type="text/javascript">alert("错误：上传文件大小超过2M或未选择文件")</script>';
        } else {
            return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . Input::get('CKEditorFuncNum') . ', "' . $userfile->url . '");</script>';
        }

    }
}