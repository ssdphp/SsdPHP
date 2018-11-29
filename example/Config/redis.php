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
/**
 * redis配置
 * 直接支持主从,支持多个redis配置链接
 * 自动将CUD 的操作进行主操作，R操作在Slave上（没有Slave操作Main）
 * Main配置必须
 */
return array(
    'redis' => array(
        'main' => array(
            array(
                'host' => "127.0.0.1",
                'port' => "6379",
                'password' => "",
                'pconnect' => false,
            )
        ),
        'slave'=>array(
            array(
                'host' => "127.0.0.1",
                'port' => "6379",
                'password' => "",
                'pconnect' => false,
            )
        )
    )
);