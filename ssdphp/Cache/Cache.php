<?php

namespace SsdPHP\Cache;
use SsdPHP\Core\Config;

class Cache
{

    /**
     * 获取的配置数据
     * @var array
     */
    public $Config=array();

    /**
     * 读取配置。设置缓存配置
     * @param $config
     */
    public function setConfig($config=[]){
        $this->Config = $config;
    }
    /**
     * 读取配置。设置缓存配置
     * @param $config
     */
    public function setAdapter($adapter="Redis"){
        $this->adapter = $adapter;
    }

    /**
     * @param array $redis_config
     * @return Adaptor\Redis
     */
    public static function getInstance($redis_config=array())
    {
        if(empty($redis_config)){
            $rc = Config::getField('redis','main');
            $redis_config = $rc[array_rand($rc)];
        }
        return new Adaptor\Redis($redis_config);
    }



    /**
     * @var array
     * key=>val
     * redis函数=>映射的操作读写
     * todo 更多
     */
    protected static $methodList = [
        'set'=>['config'=>"Main"],
        'hset'=>['config'=>"Main"],
        'hdel'=>['config'=>"Main"],
        'hgetall'=>['config'=>"Slave"],
        'hexists'=>['config'=>"Main"],
        'zadd'=>['config'=>"Main"],
        'get'=>['config'=>"Slave"],
    ];

    /**
     * 获取缓存对象
     * @param $method_name
     * @return array|mixed [zone,obj]
     */
    protected static function GetCacheInstance($CacheObj,$method_name){
        static $_Redis=[],$_Config=[];
        //default Redis['Main']
        $defaultKey = 'Main';
        //默认main配置
        if(empty(self::$methodList[$method_name]['config'])){
            self::$methodList[$method_name]['config']=$defaultKey;
        }
        //$CacheKey "Main"|"Slave"
        $CacheKey = self::$methodList[$method_name]['config'];
        //echo "init:CacheKey:$method_name,",$CacheKey,";";
        //get all config Redis["Main"|"Slave"] Default Main
        if(!empty($CacheObj->Config[$CacheKey])){
            $_Config[$CacheObj->ConfKey][$CacheKey]=$CacheObj->Config[$CacheKey];
        }else{
            $CacheKey=$defaultKey;
            if(empty($CacheObj->Config[$CacheKey])){
                throw new \Exception("Config[{$CacheObj->ConfKey}][{$CacheKey}] is empty!");
            }
            $_Config[$CacheObj->ConfKey][$CacheKey]=$CacheObj->Config[$CacheKey];
        }

        //echo "ret:CacheKey:",$CacheKey,";<br>";
        //rand get key config Redis["Main"|"Slave"][$configIndex]
        $Index = array_rand($_Config[$CacheObj->ConfKey][$CacheKey]);
        $zone = $CacheObj->ConfKey."_".$CacheKey."_".$Index;
        //echo "|",print_r($_Config,true);
        if(isset($_Redis[$zone])){
            return [
                "obj"=>$_Redis[$zone],
                "zone"=>$zone,
            ];
        }

        $className = __NAMESPACE__ . "\\Adaptor\\".$CacheObj->adapter;
        $CacheConfig = $_Config[$CacheObj->ConfKey][$CacheKey][$Index];
        $_Redis[$zone]=new $className($CacheConfig,$zone);
        return [
            "obj"=>$_Redis[$zone],
            "zone"=>$zone,
        ];
    }

    /**
     * 自动引导调用缓存
     * @param $method_name
     * @param $arguments
     * @return mixed
     */
    public function __call($method_name, $arguments)
    {
        $method_name = strtolower($method_name);
        $Cache = self::GetCacheInstance($this,$method_name);
        $Cache['obj']->zone = $Cache['zone'];
        return call_user_func_array (array(&$Cache['obj'],$method_name),$arguments);
    }

}
