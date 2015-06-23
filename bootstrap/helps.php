<?php

/**
 * 视图扩展函数
 * @param null $view 视图名
 * @param array $data 视图数据
 * @param array $mergeData 视图扩展数据
 * @return mixed
 */
function v($view = null, $data = array(), $mergeData = array())
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $trace = end($trace);
    $class_path = array_diff(explode('\\', $trace['class']), [
        'App',
        'Http',
        'Controllers'
    ]);
    $class = str_replace('Controller', '', array_pop($class_path));
    array_push($class_path, $class, $view);
    return view(implode('.', $class_path), $data, $mergeData);
}

/**
 * 获取提交的数据
 *
 * @param $key 要获取的索引
 * @param null $defaultVal
 *            默认值
 * @return mixed
 */
function I($key = null, $defaultVal = null)
{
    if (is_null($key)) {
        return Request::all();
    }
    if (Request::has($key)) {
        return Request::input($key);
    }
    return $defaultVal;
}

/**
 * 响应方法
 *
 * @param $message 消息
 * @param int $statusCode
 *            状态码
 * @return \Illuminate\Contracts\Routing\ResponseFactory \Symfony\Component\HttpFoundation\Response
 */
function R($message, $statusCode = 402)
{
    return response($message, $statusCode);
}

/**
 * 将手机号变成136-9696-4831格式
 */
function mformat($mobile)
{
    return vsprintf("%03d-%04d-%04d", [
        substr($mobile, 0, 3),
        substr($mobile, 3, 4),
        substr($mobile, 7, 4)
    ]);
}

/**
 * 商品缩略图
 */
function thumbnail($hash, $width = 150, $height = 150)
{
    $storage = \App\Storage::where('hash', $hash)->first();
    if ($storage) {
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
                $image = Image::make(public_path() . $storage->path);
                // 生成一张需求尺寸的图片，写到创建的临时文件内。
                $image->fit($width, $height);
                $image->save($tmp);

                // 计算文件的hash，并移动文件到文件存储目录。
                $_hash = md5_file($tmp);
                $_filename = $_hash . '.' . $image->extension;
                $_file_path = pathinfo($storage->path, PATHINFO_DIRNAME) . '/' . $_filename;
                $_storage = public_path() . $_file_path;
                copy($tmp, $_storage);
                unlink($tmp);

                // 保存图片信息到数据库。
                $thumbnail = \App\Storage::find($_hash);
                if (is_null($thumbnail)) {
                    $thumbnail = new \App\Storage();
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

                $hash = $_hash;
            } else {
                $hash = $thumbnail->hash;
            }
        }
    }

    return $hash;
}

/**
 * 获取客户端IP地址
 */
function getClientIp()
{
    return Request::getClientIp();
}

/**
 * 获取给定对象的模块类名
 */
function getObjectClass($object)
{
    $class = get_class($object);
    $class = explode('\\', $class);
    return 'App\\Models\\' . end($class);
}


/**
 * 生成带头像的二维码
 */
function avatarQrcode($content, $file, $avatar, $level = 0)
{
    include_once app_path('Services/phpqrcode/phpqrcode.php');

    $dir = 'qrcodes/' . date('Ym');
    if (! is_dir($dir)) {
        @mkdir($dir);
    }

    $file = "{$dir}/{$file}.png";
    QRcode::png($content, $file, $level, 16, 1);

    // 获取图片的高度
    list ($height) = getimagesize($file);

    $water_height = ceil($height / 5);

    $img = Image::make($file);

    // 加入头像
    $watermark = Image::make(public_path($avatar));
    $watermark->resize($water_height, $water_height);
    $img->insert($watermark, 'center');
    $img->save($file);

    return $file;
}

/**
 * 生成名片序列号
 */
function generateCardSn()
{
    $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

    // 查看是否已经存在
    do {
        shuffle($arr);
        $sn = implode('', array_slice($arr, 0, 6));
    } while (\App\Card::where('serial_number', $sn)->count() > 0);

    return $sn;
}

/**
 * 生成带头像的二维码
 */
function qrcode($content, $user)
{
    include_once app_path('Services/phpqrcode/phpqrcode.php');

    $dir = 'qrcodes/'.date('Ym');
    if (! is_dir($dir)) {
        @mkdir($dir);
    }

    $file = "{$dir}/activity_{$user->id}.png";
    QRcode::png($content, $file, QR_ECLEVEL_H, 16, 1);
    return $file;
}