<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/4
 * Time: 15:17
 */

namespace App\Admin\Model;

use SsdPHP\Core\Model;

class Admin_Auth_Group extends Model {

    //0-正常 1-禁用
    private $status = array(
        0=>'正常',
        1=>'禁用',
    );

    /**
     * @return array
     */
    public function getStatus(): array
    {
        return $this->status;
    }



    /**
     * 获取后台后用列表
     * @param array $cond
     * @param array $feild
     * @return mixed
     */
    public function getList($cond=array(),$feild=array("*")){

        return $this->select($cond,$feild,"","id asc");
    }


    /**
     * 通过条件获取一条记录
     * @param array $cond
     * @param array $feild
     * @return array|mixed
     */
    public function findOne($cond=array(),$feild=['*']){
        $ret = $this->selectOne($cond,$feild);
        if(!empty($ret)){
            return $ret;
        }
        return [];
    }

    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data)
    {

        if(empty($data)){
            return -1;
        }

        if(isset($data['id'])){
            unset($data['id']);
        }
        $id = $this->insert($data);

        if(!empty($id)){
           return $id;
        }
        return -2;
    }
    /**
     * 修改
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        if(empty($data) || empty($data['id'])){
            return -1;
        }
        $id = $this->update(array("id"=>intval($data['id'])),$data);

        if(!empty($id)){
            return $id;
        }
        return -1;
    }

}