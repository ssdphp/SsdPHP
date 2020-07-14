<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/4
 * Time: 15:22
 */
namespace SsdPHP\Core;

use SsdPHP\DataBase\Mysql as Db;

class Model{


    /**
     * 通过model class 设置默认的数据表
     * @var string
     */
    private $_table;
    private $_db_config=array();
    private $_db;


    /**
     * @param mixed $db_config
     */
    public function setDbConfig($db_config)
    {
        $this->_db_config = $db_config;
    }

    //初始化数据库
    public function __construct()
    {
        if(empty($this->_table)){
            $t =explode("\\",get_class($this));
            $this->_table = strtolower(array_pop($t));
        }
        $this->_db = Db::getInstance($this->_db_config);
        if(!empty($this->_table)){
            $this->_db->setTableName($this->_table);
        }
    }

    /**
     * 自动获取私有变量
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
    /**
     * 自动获取私有变量
     * @param $name
     * @return mixed
     */
    public function __set($name, $value){

        $this->$name = $value;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array (array($this->_db,$name),$arguments);
    }

    /**
     * 分页设置
     * @param int $page
     * @param int $pagesize
     */
    protected function setPage($page=1,$pagesize=10,$count=true){

        $this->_db->setPage($page);
        $this->_db->setLimit($pagesize);
        $this->_db->setCount($count);
        return $this;
    }

    /**
     * 开始事物
     * @return $this
     */
    protected function begintransaction(){
        $this->_db->begintransaction();
        return $this;
    }

    /**
     * 提交事务
     */
    protected function commit(){
        $this->_db->commit();
    }

    /**
     * 回滚事务
     */
    protected function rollback(){
        $this->_db->rollback();
    }

    protected function setTableName($table=''){

        $this->_table = $table;
        $this->_db->setTableName($table);
        return $this;
    }


    /**
     * 查询一条记录
     * @param string $condition
     * @param string $item
     * @param string $groupby
     * @param string $orderby
     * @param string $leftjoin
     * @param string $joinType
     * @return mixed
     */
    protected function selectOne($condition="",$item="",$groupby="",$orderby="",$leftjoin="",$joinType="LEFT JOIN"){

        return $this->_db->selectOne($condition,$item,$groupby,$orderby,$leftjoin,$joinType);
    }

    /**
     * 查询结果对象列表
     * @param string $condition
     * @param string $item
     * @param string $groupby
     * @param string $orderby
     * @param string $leftjoin
     * @param string $joinType
     * @return mixed
     */
    protected function select($condition="",$item="",$groupby="",$orderby="",$leftjoin="",$joinType='LEFT JOIN'){

        $ret = $this->_db->select($condition,$item,$groupby,$orderby,$leftjoin,$joinType);
        if(isset($ret->totalSecond)){
            unset($ret->totalSecond);
        }
        return $ret;
    }

    /**
     * insert
     * @param string|array|object $table
     * @param string|array|object $item
     * @param boolean $isreplace
     * @param boolean $isdelayed
     * @param string|array|object $update
     * @return int|boolean int(lastInsertId or affectedRows)
     */
    protected function insert($item="",$isreplace=false,$isdelayed=false,$update=array()){

        $last_id = $this->_db->insert($item,$isreplace,$isdelayed,$update);
        return $last_id;
    }

    /**
     * update data
     *
     * @param string|array|object $table
     * @param string|array|object $condition
     * @param string|array|object $item
     * @return int|boolean
     */
    protected function update($condition,$item){
        return $this->_db->update($condition,$item);
    }

    /**
     *
     * @param string $sql
     * @return boolean|int|array
     */
    protected function exec($sql){
        return $this->_db->execute($sql);
    }


    /**
     * 获取一条数据
     * @param array $cond
     * @param array $feild
     * @return array|mixed
     */
    public function _findone($cond=array(),$feild=["*"]){

        $ret = $this->selectOne($cond,$feild);
        if(!empty($ret)){
            return $ret;
        }
        return [];
    }

    /**
     * 添加
     */
    public function _add($data=array()){
        if(empty($data)){
            return false;
        }
        return $this->insert($data);
    }

    /**
     * 获取列表
     * @param array $cond
     * @param int $page
     * @param int $pagesize
     * @param array $field
     * @param string $order
     * @return mixed
     */
    public function _getList($cond=array(),$page=1,$pagesize=10,$field=array("*"),$order=""){

        $a = $this->setPage($page,$pagesize)
            ->select($cond,$field,"",$order);

        return $a;
    }

    /**
     * 更新
     */
    public function _updateInfo($condition,$item){

        return $this->update($condition,$item);
    }

    /**
     * 删除
     */
    public function _del($condition){

        $id = $this->delete($condition);
        return $id;
    }
}