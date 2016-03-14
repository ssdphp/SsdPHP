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

/*
 * Session = array(
 *     'new_cache_expire' => 30, //存活时间
 *     'path' => '/',
 *     'domain' => '',
 *     'secure' => false,
 *     'httponly' => true,
 *     'save_path' => null,
 *     'session_name' => 'SSDPHPSESSID',
 *     'sid_prefix' => 'SSDPHP:SESSION:',//redis key 前缀
 *     'sessionType' => '',//session类型，默认phpsession处理机制，File,Redis
 * )
 */
return array(

    "Session"=>array(
        'sessionType' => "Redis",
        'new_cache_expire' => 30,
        'sid_prefix' => "SSDPHP:SESSION:",
    ),

);