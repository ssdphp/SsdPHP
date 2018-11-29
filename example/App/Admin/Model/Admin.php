<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/4
 * Time: 15:17
 */

namespace App\Admin\Model;

use SsdPHP\Core\Model;

class Admin extends Model {

    public static $AdminConfig=array(
        'UserDataKey'=>'UserInfo',
    );
    /**
     * 后台用户登录
     * @param $loginData
     * @return mixed
     */
    public function Login($loginData){
        if(empty($loginData['username']) || empty($loginData['password'])){

            return -1;
        }
        $loginData['password'] = md5($loginData['password']);
        $data = $this->selectOne($loginData);
        if(
            !empty($data['uid'])
            && $data['username'] == $loginData['username']
            && $data['password'] == $loginData['password']
        ){
            return $data;
        }
        return -2;
    }

    /**
     * 获取后台后用列表
     * @param array $cond
     * @param array $feild
     * @return mixed
     */
    public function getList($cond=array(),$feild=array("*")){

        return $this->select($cond,$feild,"","uid desc");
    }


    /**
     * 通过uid获取后台用户信息
     * @param $uid
     * @param array $feild
     * @return array|mixed
     */
    public function getInfoByUid($uid,$feild=array("*")){

        if(empty($uid)){
            return array();
        }
        $ret = $this->selectOne(array("uid"=>$uid),$feild);
        return !empty($ret)?$ret:array();
    }
}