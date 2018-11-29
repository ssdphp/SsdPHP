<?php
namespace App\Admin\Controller;
use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Auth_Group;
use App\Admin\Model\Admin_Auth_Group_Access;
use App\Admin\Model\Admin_Menu;
use SsdPHP\Http\Input;
use SsdPHP\Http\Response;
use SsdPHP\Session\Session;
class Cauth_group extends Common {

    public function c_list(){
        $Admin_Auth_Group = new Admin_Auth_Group();
        $list = $Admin_Auth_Group->getList();
        $this->assign(array(
            'status'=>$Admin_Auth_Group->getStatus(),
            'list'=>!empty($list->items)?$list->items:array()
        ))->base();
    }

    /**
     * 添加
     */
    public function c_add(){
        $Admin_Auth_Group = new Admin_Auth_Group();
        if(Input::isPost()){
            $_POST['model']="admin";
            $ret = $Admin_Auth_Group->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,"添加成功");
            }
            Response::apiJsonResult(array(),0,'添加失败');
        }

        $this->assign(array(
            'status'=>$Admin_Auth_Group->getStatus(),
        ))->base();
    }
    /**
     * 修改
     */
    public function c_edit(){
        $Admin_Auth_Group = new Admin_Auth_Group();
        if(Input::isPost()){

            $ret = $Admin_Auth_Group->edit($_POST);
            if($ret>0){
                Response::apiJsonResult(array(),1,"修改成功");
            }
            Response::apiJsonResult(array(),1,"修改失败");
        }
        $id = Input::request("id",0,'intval');
        $info = $Admin_Auth_Group->findOne(array('id'=>$id));
        $this->assign(array(
            'info'=>$info,
            'status'=>$Admin_Auth_Group->getStatus(),
        ))->base("auth_group/add");
    }

    /**
     * 权限设置
     */
    public function c_access(){
        $qid = Input::request('qid',0,'intval');
        if($qid<1){
            exit("错误操作!");
        }
        $Admin_Menu = new Admin_Menu();
        $Admin_Auth_Group = new Admin_Auth_Group();
        $accessInfo = $Admin_Auth_Group->findOne(array('id'=>$qid));
        $inRulue = $accessInfo['rules']?explode(",",$accessInfo['rules']):array();
        $menuList = $Admin_Menu->getAll(array('status'=>1));
        $menu = array();
        $tmp = array(0=>&$menu);
        foreach($menuList as $k=>$v){
            $v['checked']=in_array($v['id'],$inRulue)?'checked':"";
            $v['child'] = array();
            $tmp[$v['pid']][$v['id']] = $v;
            $tmp[$v['id']] = &$tmp[$v['pid']][$v['id']]['child'];
        }
        $this->assign(array(
            'accessInfo'=>$accessInfo,
            'menu_node'=>$menu
        ))->base();
    }

    /**
     * 保存权限设置结果
     */
    public function c_rule_save(){
        $Admin = new Admin_Auth_Group();

        if(Input::isPost()){
            $id = Input::request('qid',0,'intval');
            $rules = Input::request('rule/a',"",'intval');
            if($id<1){
                Response::apiJsonResult(array(),1001);
            }
            if(!empty($rules)){
                $rules = implode(",",$rules);
            }
            $data=array(
                'rules'=>$rules,
                'id'=>$id,
            );
            $ret = $Admin->edit($data);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),$ret['code']);
        }
    }


    /**
     * 成员授权
     */
    public function c_user_access(){

        $qid = Input::request('qid',0,'intval');
        if($qid < 1){
            return ;
        }

        $Admin_Auth_Group = new Admin_Auth_Group();
        $accessInfo = $Admin_Auth_Group->findOne(array('id'=>$qid));

        $Admin_Auth_Group_Access = new Admin_Auth_Group_Access();

        $list = $Admin_Auth_Group_Access->findAll(array('group_id'=>$qid),array("*"),
            array("qqzan_admin"=>"qqzan_admin_auth_group_access.uid=qqzan_admin.uid")
        );

        $this->assign(array(
            'list'=>$list,
            'accessInfo'=>$accessInfo
        ))->base();
    }

    /**
     * 成员授权添加用户
     */
    public function c_user_access_add(){
        $Admin_Auth_Group_Access = new Admin_Auth_Group_Access();

        if(Input::isPost()){
            $id = Input::request('qid',0,'intval');
            $uids = Input::request('uids');
            if($id<1){
                Response::apiJsonResult(array(),1001);
            }
            if(!empty($uids)){
                $uids = explode(",",$uids);
            }
            if(empty($uids)){
                Response::apiJsonResult(array(),1001);
            }
            foreach ($uids as $uid){

                $ret = $Admin_Auth_Group_Access->add(array(
                    'group_id'=>$id,
                    'uid'=>$uid,
                ));
            }

            Response::apiJsonResult(array(),1,1006);
        }
    }
    /**
     * 解除用户授权
     */
    public function c_user_access_del(){
        $Admin_Auth_Group_Access = new Admin_Auth_Group_Access();

        if(Input::isPost()){
            $id = Input::request('qid',0,'intval');
            $uid = Input::request('uid',0,'intval');
            if($id<1){
                Response::apiJsonResult(array(),1001);
            }
            if(empty($uid)){
                Response::apiJsonResult(array(),1001);
            }
            $ret = $Admin_Auth_Group_Access->del($uid,$id);

            Response::apiJsonResult(array(),1,1006);
        }
    }
}