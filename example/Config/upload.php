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
//视图配置
use SsdPHP\SsdPHP;

return [
    //本地上传配置
    'upload'=>array(
        'local'=>array(
            'mimes'         =>array(),
            'exts'          =>array(),
            'autoSub'       =>true,
            'subName'       =>array('date', 'Ymd'),
            'rootPath'      =>SsdPHP::getAppDir().'/www/upload/',
            'savePath'      =>  '',
            //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveName'      =>  array('md5_file', ''),
            'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
            'replace'       =>  true, //存在同名是否覆盖
            'hash'          =>  true, //是否生成hash编码
            'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
            'driver'        =>  'Local', // 文件上传驱动
            'driverConfig'  =>  array(), // 上传驱动配置
        ),
        "qiniu"=>array(
            "ak"=>'ak',
            "sk"=>'sk',
            "domain"=>"http://xx.xx.com",
            "bucket"=>"bucket",
            "upurl"=>"http://up-z0.qiniu.com",
        ),
    ),


];
