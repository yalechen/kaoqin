<?php
namespace App\Services;

require_once 'vendor/autoload.php';

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class Jpush
{

    protected $app_key;

    protected $master_secret;

    protected $client;

    public function __construct()
    {
        $this->app_key = config('base_info.jpush_app_key');
        $this->master_secret = config('base_info.jpush_master_secret');
        $this->client = new JPushClient(config('base_info.jpush_app_key'), config('base_info.jpush_master_secret'));
    }

    /**
     * 对单个设备通知
     */
    public function sendByRegistrationId($registration_id, $content, $paltform = null)
    {
        try {
            if (strtolower($paltform) == 'ios') {
                // ios8 默认sound为空就没有声音，为此才多加了$paltform参数，因为无法集成，并且notification只能存一个，要么是ios，要么是android
                $result = $this->client->push()
                    ->setPlatform(M\all)
                    ->setAudience(M\all)
                    ->setOptions(M\options(null, null, null, true, null))
                    ->setNotification(M\notification(M\ios($content, 'default', null, null, null, null)))
                    ->setAudience(M\audience(M\registration_id($registration_id)))
                    ->printJSON()
                    ->send();
            } else {
                $result = $this->client->push()
                    ->setPlatform(M\all)
                    ->setAudience(M\all)
                    ->setNotification(M\notification($content))
                    ->setOptions(M\options(null, null, null, true, null))
                    ->setAudience(M\audience(M\registration_id($registration_id)))
                    ->printJSON()
                    ->send();
            }
            // echo 'Push Success.' . $br;
            // echo 'sendno : ' . $result->sendno . $br;
            // echo 'msg_id : ' . $result->msg_id . $br;
            // echo 'Response JSON : ' . $result->json . $br;
            return $result->json;
        } catch (APIRequestException $e) {
            // echo 'Push Fail.' . $br;
            // echo 'Http Code : ' . $e->httpCode . $br;
            // echo 'code : ' . $e->code . $br;
            // echo 'message : ' . $e->message . $br;
            // echo 'Response JSON : ' . $e->json . $br;
            // echo 'rateLimitLimit : ' . $e->rateLimitLimit . $br;
            // echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . $br;
            // echo 'rateLimitReset : ' . $e->rateLimitReset . $br;
            return $e->json;
        } catch (APIConnectionException $e) {
            // echo 'Push Fail: ' . $br;
            // echo 'Error Message: ' . $e->getMessage() . $br;
            // echo 'IsResponseTimeout: ' . $e->isResponseTimeout . $br;
            return $e->getMessage();
        }
    }
}