<?php
use Illuminate\Support\Facades\DB;

/**
 * 分布式事务临时解决方案
 *
 * @param Closure $callback
 * @return boolean
 */
function db_transaction(Closure $callback)
{
    DB::transaction(function ($global) use($callback)
    {
        return $callback($global);
    });
}

/**
 * 开启分布式事务
 */
function db_begin_transaction()
{
    DB::beginTransaction();
}

/**
 * 回滚分布式事务
 */
function db_rollback()
{
    DB::rollBack();
}

/**
 * 提交分布式事务
 */
function db_commit()
{
    DB::commit();
}

/**
 * 视图扩展函数
 *
 * @param null $view
 *            视图名
 * @param array $data
 *            视图数据
 * @param array $mergeData
 *            视图扩展数据
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
 * 缩略图
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
 * 创建一个分布式唯一ID
 *
 * @return string
 */
function uniqueid()
{
    $uniqid = uniqid(gethostname(), true);
    $md5 = substr(md5($uniqid), 12, 8); // 8位md5
    $uint = hexdec($md5);
    return sprintf('%s%010u', date('Ymd'), $uint);
}

/**
 * 生成唯一序列号
 */
function generateCardSn()
{
    $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

    // 查看是否已经存在
    do {
        shuffle($arr);
        $sn = implode('', array_slice($arr, 0, 6));
    } while (\App\Models\Card::where('serial_number', $sn)->count() > 0);

    return $sn;
}

/**
 * 生成带头像的二维码
 */
function qrcode($content, $user)
{
    include_once app_path('Services/phpqrcode/phpqrcode.php');

    $dir = 'qrcodes/' . date('Ym');
    if (! is_dir($dir)) {
        @mkdir($dir);
    }

    $file = "{$dir}/activity_{$user->id}.png";
    QRcode::png($content, $file, QR_ECLEVEL_H, 16, 1);
    return $file;
}

/**
 * 自定义的四舍五入
 */
function round_custom($number, $decimal = 2)
{
    return sprintf("%.{$decimal}f", round($number, 2));
}

/**
 * 指定长度的左边补零
 */
function id_pad($id, $len = 6)
{
    return str_pad($id, $len, '0', STR_PAD_LEFT);
}

/**
 * 隐藏手机号码中间4位数字
 */
function anonymous_mobile($mobile, $replace_str = '****')
{
    $mobile = str_split($mobile, 1);
    return $mobile[0] . $mobile[1] . $mobile[2] . $replace_str . $mobile[7] . $mobile[8] . $mobile[9] . $mobile[10];
}

/**
 * 根据每月的拜访次数生成拜访描述，如5天一次，2周一次
 */
function times_remark($times)
{
    if ($times == 0) {
        return '';
    }
    if ($times == 2) {
        return "半月1次";
    }
    $a = $times / 3;
    $b = $times / 4;
    if (is_int($a)) {
        for ($x = 1; $x <= 10; $x ++) {
            $c = $x * ($a / 10);
            if (strpos($c, '.') === false) {
                return "{$x}天{$c}次";
                break;
            }
        }
    }
    if (is_int($b)) {
        return "每周{$b}次";
    }
    return "每月{$times}次";
}