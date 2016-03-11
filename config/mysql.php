<?php
/*
 * mysql配置
 * 如果配置得有Slave，那么认为开启读写分离模式
 */
return array(
    "Mysql"=>array(
        //多主数据库配置,写分离
        "Main"=>array(
            array(
                'host'      =>'192.168.8.50',
                'database'  =>'test',
                'user'      =>'root',
                'password'  =>'abc123...',
                'prefix'    =>'',
                'charset'   =>'utf8',
                'port'      =>'3306',
                'engine'    =>'pdo_mysql',
            )
        ),
        //多从数据库配置
        "Slave"=>array(
            array(
                'host'      =>'192.168.8.50',
                'database'  =>'test',
                'user'      =>'root',
                'password'  =>'abc123...',
                'prefix'    =>'',
                'charset'   =>'utf8',
                'port'      =>'3306',
                'engine'    =>'pdo_mysql',
            )
        ),
    )
);