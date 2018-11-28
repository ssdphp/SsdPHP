<?php

namespace SsdPHP\DataBase;
use SsdPHP\Core\Config as Config;
use SsdPHP\SsdPHP;

class Mysql
{

    public $adapter      = "";

    public $ConfKey      = "";

    public $tablename    = "";

    public $tableprefix  = "";

    public $config       = array();


    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $DbObj
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return string
     */
    public function getConfKey()
    {
        return $this->ConfKey;
    }

    /**
     * @param string $ConfKey
     */
    public function setConfKey($ConfKey)
    {
        $this->ConfKey = $ConfKey;
    }

    /**
     * @return string
     */
    public function getTablename()
    {
        return $this->tablename;
    }

    /**
     * @param string $tablename
     */
    public function setTablename($tablename="")
    {

        if($tablename == ""){
            $this->tablename = SsdPHP::getController();
        }else{
            $this->tablename = $tablename;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTableprefix()
    {
        return $this->tableprefix;
    }

    /**
     * @param string $tableprefix
     */
    public function setTableprefix($tableprefix="")
    {
        $this->tableprefix = $tableprefix;
    }

    public function setPage($page=1){
        $this->page = $page;
        return $this;
    }

    public function setLimit($pagesize=2){
        $this->pagesize = $pagesize;
        return $this;
    }

    public function setCount($count=false){
        $this->count = $count;
        return $this;
    }

    /**
     * 数据库实例
     * @param array $db_config
     * @return Mysql
     */
    public static function getInstance($db_config=array())
    {
        $obj = new Mysql();
        //{{{设置对象缓存属性
        if(empty($db_config)){
            $db_config = Config::getField('mysql','main');
        }
        $obj->setConfig($db_config);
        //}}}
        return $obj;
    }

    /**
     * 根据PHP各种类型变量生成唯一标识号
     * @param mixed $mix 变量
     * @return string
     */
    public function to_guid_string($mix) {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }

    private static $_mysql=array();

    /**
     * 自动引导调用缓存
     * @param $method_name
     * @param $arguments
     * @return mixed
     */
    public function __call($method_name, $arguments)
    {
        $method_name = strtolower($method_name);
        $mysql_config = $this->getConfig();
        $mysql_config = $mysql_config[array_rand($mysql_config)];
        $guid = $this->to_guid_string($mysql_config);
        if(!isset(self::$_mysql[$guid])){
            self::$_mysql[$guid] = new \SsdPHP\DataBase\Adaptor\Mysql($mysql_config);
        }
        if($method_name == 'execute'){
            return self::$_mysql[$guid]->execute($arguments[0]);
        }
        if($method_name == 'begintransaction'){
            self::$_mysql[$guid]->execute('SET AUTOCOMMIT=0');
            self::$_mysql[$guid]->execute('BEGIN');
            return true;
        }
        if($method_name == 'commit'){
            self::$_mysql[$guid]->execute('COMMIT');
            self::$_mysql[$guid]->execute('SET AUTOCOMMIT=1');
            return true;
        }
        if($method_name == 'rollback'){
            self::$_mysql[$guid]->execute('ROLLBACK');
            self::$_mysql[$guid]->execute('SET AUTOCOMMIT=1');
            return true;
        }
        $table = $mysql_config['prefix'].$this->getTablename();
        array_unshift($arguments,$table);
        if(!empty($this->page) && is_numeric($this->page)){
            self::$_mysql[$guid]->setPage($this->page);
        }
        if(!empty($this->pagesize) && is_numeric($this->pagesize)){
            self::$_mysql[$guid]->setLimit($this->pagesize);
        }

        self::$_mysql[$guid]->setCount(isset($this->count) ? $this->count : false);

        return call_user_func_array (array(&self::$_mysql[$guid],$method_name),$arguments);
    }
}
