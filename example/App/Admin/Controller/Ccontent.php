<?php
namespace App\Admin\Controller;
use App\Admin\Model\Account;
use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Menu;
use App\Admin\Model\Card_No;
use App\Admin\Model\Jifen_Rule;
use App\Admin\Model\Order;
use App\Admin\Model\Product;
use App\Admin\Model\Project;
use App\Admin\Model\Zuojia_Content;
use SsdPHP\Pulgins\Http\Input;
use SsdPHP\Pulgins\Http\Response;
use SsdPHP\Pulgins\Session\Factory as Session;
use SsdPHP\Pulgins\Page\Factory as Page;
class Ccontent extends Common {

    public function __construct()
    {
        parent::__construct();
        $Project= new Project();
        $ret = $Project->getList(/*array('status'=>0)*/);
        $p =array();
        foreach ($ret->items as &$v_00){
            $p[$v_00['id']]=$v_00;
        }
        $this->assign(array(
            'project'=>$p
        ));
    }

    /**
     * 业务list
     */
    public function c_zuojia_list(){
        $_GET['page']       = Input::get('page',1,'intval');
        $_GET['pagesize']    = Input::get('pagesize',50,'intval');

        $model = new Zuojia_Content();
        $list = $model->getList(array(),$_GET['page'],$_GET['pagesize'],array("*"),"status asc");

        $Page = new Page($list->totalSize,$_GET['pagesize']);
        $this->assign(array(
            'list'=>$list,
            '_GET'=>$_GET,
            'status'=>$model->status,
            'page'=>$Page->show(),
        ))->base();
    }

    /**
     * 修改业务
     */
    public function c_zuojia_edit(){
        $id = Input::get('id');
        $model = new Zuojia_Content();
        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $Admin = new Zuojia_Content();
            $_POST=Input::post();
            $_POST['update_time']=time();
            $ret = $Admin->edit($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $this->assign(array(
            '_GET'=>$_GET,
            'info'=>$info,
            'status'=>$model->status,
        ))->base('content/zuojia_edit');
    }
    /**
     * 添加业务
     */
    public function c_zuojia_add(){
        $Admin = new Zuojia_Content();
        if(Input::isPost()){

            $_POST=Input::post();
            $ret = $Admin->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $this->assign(array(
            '_GET'=>$_GET,
            'status'=>$Admin->status,
        ))->base('content/zuojia_edit');
    }

}