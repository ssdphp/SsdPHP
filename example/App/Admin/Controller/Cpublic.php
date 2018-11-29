<?php

namespace App\Admin\Controller;

use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Auth_Group_Access;
use SsdPHP\Core\Controller;
use SsdPHP\Http\Input;
use SsdPHP\Http\Response;
use SsdPHP\Session\Session;

class Cpublic extends Controller {

    /**
     * 登录
     * 1.显示登陆界面
     * 2.处理登陆流程
     */
    public function c_login(){
        $adminconfig = Admin::$AdminConfig;
        if(Input::isPost() == true){
            $Input=array();
            $Input['username']  = Input::post("username","");
            $Input['password']  = Input::post("pwd","");
            $vcode              = Input::post("vcode","");
            $Admin = new Admin();
            $ret = $Admin->Login($Input);

            if($ret>0){

                $Admin_Auth_Group_Access = new Admin_Auth_Group_Access();
                $list = $Admin_Auth_Group_Access->ByUidGetAccess($ret['uid']);
                $ret['rules']=!empty($list['rules'])?$list['rules']:"";
                $ret['group_ids']=!empty($list['group_id'])?$list['group_id']:array();
                Session::set($adminconfig['UserDataKey'],$ret);
                Response::apiJsonResult(array("default_url"=>!empty($list['default_url'])?$list['default_url']:"/index/index"),1,"登录成功");
            }else{
                Response::apiJsonResult([],0,"登录失败$ret");
            }

        }else{

            $this->display();
        }
    }

    /**
     * 验证码
     */
    public function c_check_code(){

    }
}