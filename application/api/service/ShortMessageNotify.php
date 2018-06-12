<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/11
 * Time: 23:18
 */

namespace app\api\service;

use think\Loader;
use Aliyun\DySDKLite\SignatureHelper;

// 加载阿里云短信服务SDK文件
Loader::import("aliyun-dysms-php-sdk-lite.SignatureHelper", EXTEND_PATH, ".php");

class ShortMessageNotify extends SignatureHelper
{
    private $accessKeyID;
    private $accessKeySecret;
    private $domain;
    private $params;

    /**
     * 初始化发送短信通知所需参数
     * @param $templateParams string 短信接收号码
     * @param $templateCode string 短信模板Code
     * @param $templateParams array 短信模板参数
     *
     */
    public function __construct($phoneNumbers, $templateCode, $templateParams)
    {
        // 阿里云的AK信息
        $this->accessKeyID = config("secure.access_key_id");
        $this->accessKeySecret = config("secure.access_key_secret");

        $this->domain = 'dysmsapi.aliyuncs.com';

        $params['PhoneNumbers'] = $phoneNumbers;  // 接收短信号码
        $params['SignName'] = config("secure.sign_name");  // 短信签名
        $params['TemplateCode'] = $templateCode;  // 短信模板Code
        $params['TemplateParam'] = $templateParams;  // 短信模板参数
        if(!empty($params['TemplateParam']) && is_array($params['TemplateParam'])){
            // 将模板参数值转化为json数据格式
            $params['TemplateParam'] = json_encode($params['TemplateParam'],
                JSON_UNESCAPED_UNICODE);
        }

        $params['OutId'] = "12345";
        $params['SmsUpExtendCode'] = "1234567";
        $params['RegionId'] = "cn-hangzhou";
        $params['Action'] = "SendSms";
        $params['Version'] = "2017-05-25";
        $this->params = $params;
    }

    /**
     * 调用阿里云短信服务SDK发送短信
     * @return object 短信发送状态
     */
    public function send(){
        $content = $this->request($this->accessKeyID, $this->accessKeySecret, $this->domain, $this->params);
        return $content;
    }
}