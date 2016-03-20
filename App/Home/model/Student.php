<?php

namespace  App\Home\model;

use SsdPHP\Core\Factory as SFactory;

class Student{

    public function Login($account,$pwd){

        if(empty($account) || empty($pwd)){
            return -1;
        }

        $s = SFactory::getInstance('SsdPHP\Pulgins\DataBase\Factory',"ip")->selectOne();
        return $s;
    }
}