<?php
/*
 * mysql配置
 * 如果配置得有Slave，那么认为开启主从读写分离模式
 */
return array(
    "mysql"=>array(
        //多主数据库配置,写分离
        "main"=>array(
            array(
                'host'      =>'localhost',
                'database'  =>'testdb',
                'user'      =>'test',
                'password'  =>'test',
                'prefix'    =>'ssd_',
                'charset'   =>'utf8mb4',
                'port'      =>'3306',
                'engine'    =>'pdo_mysql',
            )
        ),"slave"=>array(
            array(
                'host'      =>'localhost',
                'database'  =>'testdb',
                'user'      =>'test',
                'password'  =>'test',
                'prefix'    =>'ssd_',
                'charset'   =>'utf8mb4',
                'port'      =>'3306',
                'engine'    =>'pdo_mysql',
            )
        )
    )
);