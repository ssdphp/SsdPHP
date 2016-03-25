<?php

namespace SsdPHP\Pulgins\DataBase;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;
use SsdPHP\SsdPHP;

class Factory
{

    private static $table = "";
    private static $prefix = "";
    private static $_instance = null;

    public function __construct($table="")
    {
        if($table == ""){
            self::$table = SsdPHP::getAction();
        }else{
            self::$table = $table;
        }
        self::getInstance();
    }

    public static function getInstance($adapter = 'Mysql', $config = null)
    {
        if(isset(self::$_instance)){
            return self::$_instance;
        }
        if(empty($config)){
            $config = SConfig::getField("Mysql","Main");
        }
        if(!empty($config['prefix']))
            self::$prefix = $config['prefix'];

        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        self::$_instance = SFactory::getInstance($className, $config);
    }

   /* public function select($condition="",$item="",$groupby="",$orderby="",$leftjoin=""){
        return self::$_instance->select(self::$table,$condition,$item,$groupby,$orderby,$leftjoin);
    }

    public function selectOne($condition="",$item="",$groupby="",$orderby="",$leftjoin=""){
        return self::$_instance->selectOne(self::$table,$condition,$item,$groupby,$orderby,$leftjoin);
    }
    public function update($condition,$item){
        return self::$_instance->update(self::$table,$condition,$item);
    }
    public function delete($condition){
        return self::$_instance->delete(self::$table,$condition);
    }
    public function insert($item="",$isreplace=false,$isdelayed=false,$update=array()){
        return self::$_instance->delete(self::$table,$item,$isreplace,$isdelayed,$update);
    }*/

    public function __call($name, $arguments)
    {
        $count = count($arguments);
        $arguments[0] = self::$prefix.self::$table;
        switch($count){

            case 0:
                $res = self::$_instance->$name($arguments[0]);
                break;
            case 3:
                $res = self::$_instance->$name($arguments[0],$arguments[1],$arguments[2]);
                break;
            case 2:
                $res = self::$_instance->$name($arguments[0],$arguments[1]);
                break;
            case 4:
                $res = self::$_instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
                break;
            case 5:
                $res = self::$_instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4]);
                break;
            case 6:
                $res = self::$_instance->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3],$arguments[4],$arguments[5]);
                break;
        }

        return $res;
    }
}
