<?php
namespace App\Task;

use SsdPHP\Core\Config as SConfig;

class Sms{


    public static function sendTextMessage($toPhone,$Content="",$callback=null,array $config){
        //短信URL
        $SMS_SEND_ONE_URL   = SConfig::getField('SMS','SMS_SEND_ONE_URL',"http://mb345.com:999/ws/batchSend.aspx?CorpID={\$SMS_ACCOUNT}&Pwd={\$SMS_PWD}&Mobile={$toPhone}&Cell=&SendTime=&Content={$Content}");
        //短信帐号
        $SMS_ACCOUNT        = SConfig::getField('SMS','SMS_ACCOUNT');
        //短信密码
        $SMS_PWD            = SConfig::getField('SMS','SMS_PWD');
        $SMS_CHARSET        = SConfig::getField('SMS','SMS_CHARSET',"GBK");
        $SMS_SEND_ONE_URL=preg_replace(
            array('/\{\$SMS_ACCOUNT\}/','/\{\$Pwd\}/','/\{\$Mobile\}/','/\{\$Content\}/'),
            array($SMS_ACCOUNT,$SMS_PWD,$toPhone,iconv('UTF-8', $SMS_CHARSET, $Content)),
            $SMS_SEND_ONE_URL
        );
        if(SConfig::getField('SMS','SMS_IS_DEVELOP',false) === false){
            $result = file_get_contents($SMS_SEND_ONE_URL);
            $result = $result >= 0 ? 1 : 0;
        }else{
            $result = 1;//开发模式，始终成功
        }
        $callbackData = array(
            'phone' => $toPhone,
            'status' => $result,
            'time' => time(),
            'content' => $Content,
            'type' => SConfig::getField('SMS','SMS_TYPE'),
        );
        if(is_callable($callback)){
            call_user_func_array($callback,array("data"=>$callbackData));
        }
        return ;
    }



}