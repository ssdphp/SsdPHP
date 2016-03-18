<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SsdPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2015-2020. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.SsdPHP.com                                    |
+-----------------------------------------------------------------------+
}}}*/
//邮件配置
return array(

    'Email'=>array(
        'Main'=>array(
            //SMTP servers
            'Host'=>"smtp.mxhichina.com",
            //SMTP username  注意：普通邮件认证不需要加 @域名
            'Username'=>"postmaster@xxxx.com",
            //SMTP password
            'Password'=>"xxxxx",
            //发件人邮箱
            'From'=>"postmaster@bynxl.com",
            //发件人昵称
            'FromName'=>"www.ssdphp.com",
            //发送邮件类型，如，找回密码，账号注册等等
            'EmailType'=>"1",
        ),
    )
);