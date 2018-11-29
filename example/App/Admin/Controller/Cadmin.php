<?php
namespace App\Admin\Controller;
use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Menu;
use App\Admin\Model\Ditch;
use SsdPHP\Http\Input;
use SsdPHP\Http\Response;
use SsdPHP\Session\Session;
class Cadmin extends Common {

    public function c_admin_list(){
        $Admin = new Admin();
        $list = $Admin->getList();
        $this->assign(array(
            'list'=>!empty($list->items)?$list->items:array()
        ))->base();
    }

    /**
     * 后台管理用户添加
     */
    public function c_admin_add(){

        if(Input::isPost()){
            $Admin = new Admin();
            $_POST=Input::post();
            $ret = $Admin->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
        }
        $this->base();
    }
    /**
     * 后台管理用户修改
     */
    public function c_admin_edit(){
        $Admin = new Admin();
        if(Input::isPost()){

            $ret = $Admin->edit($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
        }
        $Ditch = new Ditch();

        $uid = Input::request("uid",0,'intval');
        $dinfo = $Ditch->findOne(array('tui_uid'=>$uid));

        $info = $Admin->getInfoByUid($uid);
        $this->assign(array(
            'info'=>$info,
            'dinfo'=>$dinfo
        ))->base("admin/admin_add");
    }

    public function c_menu(){
        $Admin_Menu = new Admin_Menu();
        $list = $Admin_Menu->getTreeAll();
        $del_list = $Admin_Menu->getAll(array('is_del'=>1));
        //print_r($list);
        $this->assign(array(
            '_GET'=>$_GET,
            'list'=>$list,
            'del_list'=>$del_list,
        ))->base();
    }

    //菜单排序
    public function c_menu_order(){
        $data = Input::request('data');
        if(!empty($data)){
            $data = json_decode($data,true);
            if(!empty($data)){
                $Admin_Menu = new Admin_Menu();
                foreach ($data as $k=>$v){
                    $s=$Admin_Menu->edit(['id'=>$v['id'],'sort'=>$k,'is_del'=>0,'sort_pid'=>0]);
                    if(!empty($v['children'])){
                        foreach ($v['children'] as $_k=>$_v){
                            $s=$Admin_Menu->edit(['id'=>$_v['id'],'sort'=>$_k,'is_del'=>0,'sort_pid'=>$v['id']]);

                            if(!empty($_v['children'])){
                                foreach ($_v['children'] as $__k=>$__v){
                                    $s=$Admin_Menu->edit(['id'=>$__v['id'],'sort'=>$__k,'is_del'=>0,'sort_pid'=>$_v['id']]);
                                }
                            }
                        }
                    }
                }
                Response::apiJsonResult([],1,'排序成功');
            }
        }
        Response::apiJsonResult([],0,'排序失败');
    }
    //
    public function c_menu_info(){
        $Admin_Menu = new Admin_Menu();
        $id = Input::request('id');
        $ret = $Admin_Menu->getMenuById($id);
        Response::apiJsonResult($ret,1);
    }
    /**
     * 后台菜单添加
     */
    public function c_menu_add(){


        if(Input::isPost()){
            $Admin_Menu = new Admin_Menu();
            $ret = $Admin_Menu->add($_POST);
            if($ret>0){
                Response::apiJsonResult(array(),1,"添加成功");
            }
            Response::apiJsonResult(array(),0,"添加失败");
        }else{
            $_GET['pid'] = Input::get('pid',0,'intval');
            $this->assign(array(
                '_GET'=>$_GET,
            ))->base();
        }

    }
    /**
     * 后台菜单添加
     */
    public function c_menu_edit(){

        $Admin_Menu = new Admin_Menu();
        if(Input::isPost()){
            $_POST['update_time']=time();
            $ret = $Admin_Menu->edit($_POST);
            if(Input::get('active',"") == "del"){
                if($ret>0){
                    Response::apiJsonResult(array(),1,"删除成功。");
                }
                Response::apiJsonResult(array(),0,'删除失败');
            }
            if($ret>0){
                Response::apiJsonResult($_POST,1,"修改成功");
            }
            Response::apiJsonResult(array(),0,'状态修改失败');
        }else{
            $_GET['id'] = Input::get('id',0,'intval');
            $_GET['pid'] = Input::get('pid',0,'intval');
            $info = $Admin_Menu->getMenuById($_GET['id']);
            $this->assign(array(
                '_GET'=>$_GET,
                'info'=>$info,
            ))->base('admin/menu_add');
        }

    }
    /**
     * 退出登录
     */
    public function c_loginout(){
        Session::destroy();
        if(Input::isAJAX()){
            Response::apiJsonResult([],1);
        }else{
            header("location:/public/login.html");
        }

    }
}