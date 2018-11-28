<?php

namespace SsdPHP\Email\Adaptor;


class Email
{
    private $Email;
    private $config = array(
        //SMTP servers
        'Host'=>"smtp.mxhichina.com",
        //SMTP username  注意：普通邮件认证不需要加 @域名
        'Username'=>"xx@163.com",
        //SMTP password
        'Password'=>"xxxx",
        //这里指定字符集！
        'CharSet'=>"utf-8",
        'Encoding'=>"base64",
        'AltBody'=>"text/html",
        'From'=>"xxx",//发件人邮箱
        'FromName'=>"xxxx",//发件人昵称
    );
    public function __construct($config=array())
    {
        if(!empty($config)){
            $this->config = array_merge($this->config,$config);
        }
        $this->Email = new PHPMailer();
    }

    /**
     * $mail= $mail->send_mail("xzh_tx@163.com", "小辉辉","Test", "欢迎使用ssdphp");
     * @author  xiaohuihui  <xzh_tx@163.com>
     * @param $sendto_email 收件人邮箱
     * @param $toname 收件人姓名
     * @param $title 邮件主题
     * @param $body 邮件内容-支持html
     * @return bool 发送状态，true-成功，false-失败
     */
    public function send_mail($sendto_email, $toname, $title="", $body=""){

        $this->Email->IsSMTP();                  // send via SMTP
        $this->Email->SMTPAuth = true;
        $this->Email->Host = $this->config['Host'];   // SMTP servers
        $this->Email->Username = $this->config['Username'];     // SMTP username  注意：普通邮件认证不需要加 @域名
        $this->Email->Password = $this->config['Password']; // SMTP password
        $this->Email->From = $this->config['From'];      // 发件人邮箱
        $this->Email->FromName =  $this->config['FromName'];  // 发件人

        $this->Email->CharSet = $this->config['CharSet'];   // 这里指定字符集！
        $this->Email->Encoding = $this->config['Encoding'];
        $this->Email->AddAddress($sendto_email,$toname);  // 收件人邮箱和姓名
        //$this->Email->AddReplyTo("postmaster@bynxl.com","bynxl.com");
        //$mail->WordWrap = 50; // set word wrap 换行字数
        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment 附件
        //$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
        $this->Email->IsHTML(true);  // send as HTML
        // 邮件主题
        $this->Email->Subject = $title;
        $this->Email->AltBody ="text/html";
        // 邮件内容
        $this->Email->Body = '
        <html><head><meta http-equiv="Content-Language" content="zh-cn"><meta http-equiv="Content-Type" content="text/html; charset='.$this->config['CharSet'].'"></head><body>'.$body.'</body></html>';

        if(!$this->Email->Send())
        {
            trigger_error("邮件错误信息: " . $this->Email->ErrorInfo);
            return false;
        }
        else {
            return true;
        }
    }
}
?>