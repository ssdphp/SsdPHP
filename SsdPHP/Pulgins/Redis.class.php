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
namespace pulgins;
class Redis{
    public function __construct($zone="REDIS"){
        $hosts=array();
        $config = Tool::Config($zone);
        if(!empty($config)){
            foreach($config as $host){
                $hosts[]=$host['host'].":".$host['port'];
            }
        }
        self::$_config = $hosts;
    }
    private static $_rc;
    private static $_config;

    /**
     * 使用reids配置
     * author xiaohuihui <xzh_tx@163.com>
     * @param string $zone
     */
    public function useConfig($zone="REDIS"){
        $hosts=array();

        if(!empty(self::$_config)){
            $config = Tool::Config($zone);
            foreach($config as $host){
                $hosts[]=$host['host'].":".$host['port'];
            }
        }
        self::$_rc = new \RedisArray($hosts);
    }
    public function __call($name,$args){
        return  call_user_func_array (array(self::$_rc,$name),$args);
    }
}