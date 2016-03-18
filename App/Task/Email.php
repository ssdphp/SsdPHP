<?php
namespace App\Task;

use SsdPHP\Pulgins\Email\Factory as EmailFactory;
use SsdPHP\Core\Config as SConfig;

class Email{

    /**
     * 发送邮件
     * @see RegShutdownEvent::add('Home\controller\index::taskEmail','xzh_tx@163.com','xhh','test','body');
     * @author  xiaohuihui  <xzh_tx@163.com>
     * @param $toEmail
     * @param $toUser
     * @param $title
     * @param $body
     */
    public static function sendEmail($toEmail,$toUser,$title,$content,$callback=null){
        if(!$toEmail || !$title){
            return ;
        }
        $result = EmailFactory::getInstance()->send_mail($toEmail,$toUser,$title,$content);
        $callbackData = array(
            'email' => $toEmail,
            'status' => (int)$result,
            'time' => time(),
            'content' => $content,
            'type' => SConfig::getField('Email','Main')['EmailType'],
        );
        if(is_callable($callback)){
            call_user_func_array($callback,array("data"=>$callbackData));
        }
    }

    public static function callback($data){
        // todo
    }

}