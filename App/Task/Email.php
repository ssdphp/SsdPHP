<?php
namespace App\Task;

use SsdPHP\Pulgins\Email\Factory as EmailFactory;

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
    public static function taskEmail($toEmail,$toUser,$title,$body){
        if(!$toEmail || !$title){
            return ;
        }
        EmailFactory::getInstance()->send_mail($toEmail,$toUser,$title,$body);
    }



}