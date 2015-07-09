<?php

namespace App\Http\Controllers;

use Request as I;
use Response as R;
use Auth;
use App\Models\Storage;
use App\Models\UserFile;
use Crypt;
use App\Models\User;
use Validator;
use Image;

class StorageController extends Controller
{

    protected $path;

    protected $file_name;

    public function __construct($file_name = '')
    {
        $this->path = '/upload_files';
        if (! empty($file_name)) {
            $this->file_name = $file_name;
        } else {
            $this->file_name = I::input('file_name', 'file');
        }
        // 获取存储目录
    }

    /**
     * 获取文件
     */
    public function getFile()
    {
        // 验证数据。
        $validator = Validator::make(I::all(), [
            'hash' => [
                'required',
                'exists:storages,hash'
            ]
        ], [
            'hash.required' => '参数错误 ',
            'hash.exists' => '没有找到相关文件'
        ]);

        if ($validator->fails()) {
            return R::make($validator->messages()->first(), 402);
        }

        $storage = Storage::find(I::input('hash'));

        // 取得文件名与拓展名。
        $filename = $storage->hash;

        $disposition = 'attachment';
        $headers = [
            'Content-Type' => $storage->mime
        ];

        // 根据文件Mime处理额外操作。
        switch ($storage->mime) {

            // 图片。
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
            case 'image/tiff':

                // 取得欲取的图片尺寸。
                $width = I::input('width', 0);
                $height = I::input('height', 0);
                $width = $width > 0 ? ceil($width) : null;
                $height = $height > 0 ? ceil($height) : null;

                // 如果是要取缩略图。
                if ((! is_null($width) || ! is_null($height)) && ($storage->width != $width || $storage->height != $height)) {
                    // 查找当前图片的缩略图列表，是否已经有此尺寸的图片。
                    if (is_null($width) && ! is_null($height)) {
                        $width = ceil($storage->width / $storage->height * $height);
                    }
                    if (is_null($height) && ! is_null($width)) {
                        $height = ceil($storage->height / $storage->width * $width);
                    }
                    $thumbnail = is_null($storage) ? null : $storage->thumbnails()
                        ->where('width', $width)
                        ->where('height', $height)
                        ->first();

                    // 如果指定尺寸的缩略图不存在，则生成并保存到数据库中。
                    if (is_null($thumbnail)) {

                        // 在缓存目录内创建一个pic前缀的临时文件，并修改推展名。
                        $tmp = tempnam(storage_path('cache'), 'pic');
                        rename($tmp, $tmp .= '.jpg');
                        // 打开原图。
                        $image = Image::make($this->storage_path . $storage->path);
                        // 生成一张需求尺寸的图片，写到创建的临时文件内。
                        $image->fit($width, $height);
                        $image->save($tmp);

                        // 计算文件的hash，并移动文件到文件存储目录。
                        $_hash = md5_file($tmp);
                        $_filename = $_hash . '.' . $image->extension;
                        $_file_path = pathinfo($storage->path, PATHINFO_DIRNAME) . '/' . $_filename;
                        $_storage = $this->storage_path . $_file_path;
                        copy($tmp, $_storage);
                        unlink($tmp);

                        // 保存图片信息到数据库。
                        $thumbnail = Storage::find($_hash);
                        if (is_null($thumbnail)) {
                            $thumbnail = new Storage();
                            $thumbnail->hash = $_hash;
                            $thumbnail->parent_hash = $storage->hash;
                            $thumbnail->filename = $storage->filename;
                            $thumbnail->size = filesize($_storage);
                            $thumbnail->width = $image->width();
                            $thumbnail->height = $image->height();
                            $thumbnail->mime = $image->mime;
                            $thumbnail->format = $storage->format;
                            $thumbnail->path = $_file_path;
                            $thumbnail->save();
                        }
                    }

                    // 此次请求使用此缩略图。
                    $storage = $thumbnail;
                    $filename .= "_{$storage->width}_{$storage->height}";
                }

                $disposition = 'inline';
                break;

            // 视频。
            case 'video/x-ms-asf':
            case 'video/x-ms-asf':
            case 'video/avi':
            case 'video/x-ivf':
            case 'video/x-mpeg':
            case 'video/x-mpeg':
            case 'video/mpeg4':
            case 'video/x-sgi-movie':
            case 'video/mpeg':
            case 'video/mpeg4':
            case 'video/x-mpg':
            case 'video/x-mpeg':
            case 'video/mpg':
            case 'video/mpg':
            case 'video/x-mpeg':
            case 'video/mpg':
            case 'video/mpeg':
            case 'video/vnd.rn-realvideo':
            case 'video/x-ms-wm':
            case 'video/x-ms-wmv':
            case 'video/x-ms-wmx':
            case 'video/x-ms-wvx':
            case 'video/quicktime':
                unset($headers['Content-Type']);
                $disposition = 'inline';
                break;

            // 音频。
            case 'audio/x-mei-aac':
            case 'audio/aiff':
            case 'audio/aiff':
            case 'audio/aiff':
            case 'audio/basic':
            case 'audio/x-liquid-file':
            case 'audio/x-liquid-secure':
            case 'audio/x-la-lms':
            case 'audio/mpegurl':
            case 'audio/mid':
            case 'audio/mid':
            case 'audio/x-musicnet-download':
            case 'audio/x-musicnet-stream':
            case 'audio/mp1':
            case 'audio/mp2':
            case 'audio/mp3':
            case 'audio/rn-mpeg':
            case 'audio/scpls':
            case 'audio/vnd.rn-realaudio':
            case 'audio/x-pn-realaudio':
            case 'audio/mid':
            case 'audio/x-pn-realaudio':
            case 'audio/x-pn-realaudio-plugin':
            case 'audio/basic':
            case 'audio/wav':
            case 'audio/x-ms-wax':
            case 'audio/x-ms-wma':
            case 'audio/scpls':
                $disposition = 'inline';
                break;
        }

        // 处理文件名。
        $filename = str_replace('%', 'x', $filename);

        // 生成指定Mime的数据响应。
        return R::download(public_path().$storage->path, $filename, $headers, I::input('download', 'false') == 'true' ? 'attachment' : $disposition);
    }

    /**
     * 上传文件
     */
    public function postFile()
    {
        // 验证数据。
        if (! I::hasFile($this->file_name)) {
            return R::make('没有上传文件', 402);
        }

        // 取得上传的文件。
        $file = I::file($this->file_name);

        // 取得文件的hash，文件路径与文件名。
        $hash = md5_file($file->getRealPath());
        // 取得hash的文件。
        $storage = Storage::find($hash);

        // 如果文件不存在，则创建文件。
        if (is_null($storage)) {
            // 获取文件ID3信息。
            include app_path() . '/Services/getID3/getid3.php';

            $info = with(new \getID3())->analyze($file->getRealPath());
            // 修复ID3库对不支持的文件格式的处理。
            if (! isset($info['mime_type'])) {
                $info['mime_type'] = mime_content_type($file->getRealPath());
            }
            if (! isset($info['fileformat'])) {
                $info['fileformat'] = $file->getClientOriginalExtension();
            }
            $info['filesize'] = filesize($file->getRealPath());

            $arr_tmp = str_split(md5(microtime(true)), 2);
            $filename = "{$hash}." . $file->getClientOriginalExtension();
            //$file_path = $arr_tmp[0] . '/' . $arr_tmp[1] . '/' . $arr_tmp[2] . '/';
            $file_path = date('Y') . date('m') . DIRECTORY_SEPARATOR ;

            $storage = new Storage();
            $storage->hash = $hash;
            $storage->filename = $file->getClientOriginalName();
            $storage->format = @$info['fileformat'] ?  : '';
            $storage->size = @$info['filesize'] ?  : 0;
            $storage->width = @$info['video']['resolution_x'] ?  : 0;
            $storage->height = @$info['video']['resolution_y'] ?  : 0;
            $storage->seconds = @$info['playtime_seconds'] ?  : 0;
            $storage->mime = @$info['mime_type'] ?  : '';
            $storage->path = $this->path . '/' . $file_path . $filename;

            $storage->save();
            // 移动文件到存储目录
            $file->move(public_path() . $this->path . '/' . $file_path, $filename);
        }

        if (Auth::check()) {
            // 关联用户的视频。
            $userfile = new UserFile();
            $userfile->user()->associate(Auth::user());
            $userfile->storage()->associate($storage);
            $userfile->save();
        }

        if (I::has('uploader')) {
            return [
                'status' => 1,
                'type' => 'ajax',
                'name' => $storage->filename,
                'url' => $storage->path,
                'hash' => $storage->hash
            ];
        }

        return $storage;
    }

    /**
     * 非ajax上传文件
     */
    public function formUploadFile()
    {
        $store = $this->postFile();
        echo $store;
    }
}