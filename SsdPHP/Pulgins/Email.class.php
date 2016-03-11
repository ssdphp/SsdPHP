<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SlightPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2008-2009. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.slightphp.com                                    |
+-----------------------------------------------------------------------+
}}}*/
namespace pulgins;

use pulgins\mail\PHPMailer;

class Email extends PHPMailer{
    /**
     * 参数说明(发送到, 邮件主题, 邮件内容, 附加信息, 用户名)
     * $mail = new \pulgins\Email();
     * $mail= $mail->send_mail("xzh_tx@163.com", "【不用你下楼】注册验证","xiaoxie", "你的验证码是：12345");
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $sendto_email 发送到
     * @param $subject 邮件主题
     * @param $user_name 用户名
     * @param string $body 邮件内容-支持html
     * @param string $From 发件人邮箱
     * @param string $FromName 发件人昵称
     * @return bool 发送状态，true-成功，false-失败
     */
    public function send_mail(
        $sendto_email,
        $subject,
        $user_name,
        $body="不用你下楼",
        $From="postmaster@bynxl.com",
        $FromName="【不用你下楼】项目团队"
    ){

        $this->IsSMTP();                  // send via SMTP
        $this->Host = "smtp.mxhichina.com";   // SMTP servers
        $this->SMTPAuth = true;
        $this->Username = "postmaster@bynxl.com";     // SMTP username  注意：普通邮件认证不需要加 @域名
        $this->Password = "xxxxx"; // SMTP password
        $this->From = $From;      // 发件人邮箱
        $this->FromName =  $FromName;  // 发件人

        $this->CharSet = "utf-8";   // 这里指定字符集！
        $this->Encoding = "base64";
        $this->AddAddress($sendto_email,$user_name);  // 收件人邮箱和姓名
        $this->AddReplyTo("postmaster@bynxl.com","bynxl.com");
        //$mail->WordWrap = 50; // set word wrap 换行字数
        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment 附件
        //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
        $this->IsHTML(true);  // send as HTML
        // 邮件主题
        $this->Subject = $subject;
        // 邮件内容
        $this->Body = '
        <html><head>
        <meta http-equiv="Content-Language" content="zh-cn">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        </head>
        <body>
        '.$body.'
        </body>
        </html>
        ';
        $this->AltBody ="text/html";
        if(!$this->Send())
        {
            trigger_error("邮件错误信息: " . $this->ErrorInfo);
            return false;
        }
        else {
            return true;
        }
    }
}
?>