<?php
namespace App\Admin\Controller;
use App\Admin\Model\Account;
use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Menu;
use App\Admin\Model\Adv;
use App\Admin\Model\Card_No;
use App\Admin\Model\Jifen_Rule;
use App\Admin\Model\Order;
use App\Admin\Model\Product;
use App\Admin\Model\Product_Category_Cat;
use App\Admin\Model\Project;
use App\Admin\Model\Product_Category;
use App\Admin\Model\Zuojia_Product;
use App\Admin\Model\Zuojia_Product_Catgroy;
use SsdPHP\Core\Config;
use SsdPHP\Pulgins\Http\Input;
use SsdPHP\Pulgins\Http\Response;
use SsdPHP\Pulgins\Session\Factory as Session;
use SsdPHP\Pulgins\Page\Factory as Page;
class Cshop extends Common {

    public function __get($name)
    {
        // TODO: Implement __get() method.
        $Project = new Zuojia_Product();
        $name =str_replace("_","","get". $name);
        return $Project->$name();
    }

    public function __construct()
    {
        parent::__construct();
        $Project = new Zuojia_Product();
        $this->assign(
            'icon_type',$Project->getIconType()
        );
    }
    /**
     * 业务list
     */
    public function c_product_categroy(){

        $_GET['page'] = Input::request('page',1,'intval');
        $_GET['k'] = Input::request('k','product/categroy','');
        $model = new Zuojia_Product_Catgroy();

        $_GET['is_shangjia'] = Input::request('is_shangjia',1);
        $list = $model->getList(array('is_shangjia'=>$_GET['is_shangjia']),array("*"));
        //print_r($list);
        //$Page = new Page($list->totalSize,$_GET['pagesize']);
        $ccatlist = $model->getList();
        $index=array();
        foreach ($ccatlist as $v){
            $index[$v['id']]=$v;
        }
        $this->assign(array(
            'list'=>$list,
            '_GET'=>$_GET,
            'index'=>$index,
            'status'=>$model->status,
            'ccat'=>$model->ccat,
            'is_shangjia'=>$model->is_shangjia,
        ))->base();
    }

    /**
     * 业务list
     */
    public function c_ccat(){

        $_GET['page'] = Input::request('page',1,'intval');
        $_GET['k'] = Input::request('k','product/ccat','');
        $_GET['is_shangjia'] = Input::request('is_shangjia',1);
        $model = new Product_Category_Cat();
        $Product_Category_Cat = new Product_Category_Cat();
        $list = $model->getList(array('is_shangjia'=>$_GET['is_shangjia']),array("*"));
        //print_r($list);
        //$Page = new Page($list->totalSize,$_GET['pagesize']);
        $this->assign(array(
            'list'=>$list,
            '_GET'=>$_GET,
            'status'=>$model->status,
            'ccat'=>$Product_Category_Cat->ccat,
            'is_shangjia'=>$model->is_shangjia,
        ))->base();
    }

    /**
     * 业务list
     */
    public function c_product(){
        $_GET['page']       = Input::get('page',1,'intval');
        $_GET['pagesize']    = Input::get('pagesize',100,'intval');
        $_GET['is_shangjia']    = Input::get('is_shangjia',1,'intval');
        $_GET['pid']    = Input::get('pid',0,'intval');
        $adv = new Adv();

        $Product_Category = new Zuojia_Product_Catgroy();
        $Zuojia_Product = new Zuojia_Product();

        $cond = array();
        $cond['is_shangjia']=$_GET['is_shangjia'];
        if(!empty($_GET['pid'])){
            $cond['cid']=$_GET['pid'];
        }
        $list = $Zuojia_Product->getList($cond,$_GET['page'],$_GET['pagesize'],array("*"),"sort desc");
        $Page = new Page($list->totalSize,$_GET['pagesize']);
        $this->assign(array(
            'list'=>$list,
            '_GET'=>$_GET,
            'page'=>$Page->show(),
            'status'=>$this->status,
            'shua_pingtai'=>$this->shua_pingtai,
            'page_type'=>$this->page_type,
            'need_aid'=>$this->need_aid,
            'project'=>$adv->getProject(),
            'is_shangjia'=>$Product_Category->is_shangjia,
        ))->base();
    }



    /**
     * 修改业务
     */
    public function c_product_edit(){
        $id = Input::get('id');
        $model = new Zuojia_Product();
        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $Admin = new Zuojia_Product();
            $_POST=Input::post();
            $_POST['update_time']=time();
            $ret = $Admin->edit($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $PROJECT_JS_MODEL = Config::get('PROJECT_JS_MODEL',$this->project_cate);
        $Product_Category = new Zuojia_Product_Catgroy();
        $plist = $Product_Category->getList(array('is_shangjia'=>$info['is_shangjia']));
        $this->assign(array(
            '_GET'=>$_GET,
            'info'=>$info,
            'status'=>$this->status,
            'plist'=>$plist,
            'top'=>$Product_Category->top,
            'shua_pingtai'=>$this->shua_pingtai,
            'page_type'=>$this->page_type,
            'project_cate'=>$PROJECT_JS_MODEL,
        ))->base();
    }
    /**
     * 添加业务
     */
    public function c_product_add(){
        $Admin = new Zuojia_Product();
        if(Input::isPost()){

            $_POST=Input::post();
            $ret = $Admin->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $Product_Category = new Zuojia_Product_Catgroy();
        $plist = $Product_Category->getList();
        $PROJECT_JS_MODEL = Config::get('PROJECT_JS_MODEL',$this->project_cate);
        $this->assign(array(
            '_GET'=>$_GET,
            'status'=>$this->status,
            'shua_pingtai'=>$this->shua_pingtai,
            'top'=>$Product_Category->top,
            'plist'=>$plist,
            'page_type'=>$this->page_type,
        ))->base('zuojia_shop/product_edit');
    }

    /**
     * 修改业务
     */
    public function c_product_categroy_edit(){
        $id = Input::get('id');
        $model = new Zuojia_Product_Catgroy();

        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $_POST=Input::post();
            $_POST['update_time']=time();
            $ret = $model->edit($_POST);
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
            'is_shangjia'=>$model->is_shangjia,
        ))->base();
    }
    /**
     * 修改业务
     */
    public function c_edit(){
        $id = Input::get('id');
        $model = new Product_Category();

        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $_POST=Input::post();
            $_POST['update_time']=time();
            $ret = $model->edit($_POST);
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
            'is_shangjia'=>$model->is_shangjia,
        ))->base();
    }
    /**
     * 修改业务
     */
    public function c_ccatedit(){
        $id = Input::get('id');
        $model = new Product_Category_Cat();
        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $_POST=Input::post();
            $_POST['update_time']=time();
            $ret = $model->edit($_POST);
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
            'is_shangjia'=>$model->is_shangjia,
        ))->base();
    }
    /**
     * 修改业务
     */
    public function c_ccatadd(){
        $Admin = new Product_Category_Cat();
        if(Input::isPost()){
            $_POST=Input::post();
            $ret = $Admin->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $this->assign(array(
            '_GET'=>$_GET,
            'status'=>$Admin->status,
            'is_shangjia'=>$Admin->is_shangjia,
        ))->base('product/ccatedit');
    }
    /**
     * 添加业务
     */
    public function c_add(){
        $Admin = new Product_Category();
        $Product_Category_Cat = new Product_Category_Cat();
        if(Input::isPost()){
            $_POST=Input::post();
            $ret = $Admin->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $ccatlist = $Product_Category_Cat->getList();
        $this->assign(array(
            '_GET'=>$_GET,
            'status'=>$Admin->status,
            'ccatlist'=>$ccatlist,
            'is_shangjia'=>$Admin->is_shangjia,
        ))->base('product/edit');
    }/**
     * 添加业务
     */
    public function c_product_categroy_add(){
        $Admin = new Zuojia_Product_Catgroy();
        if(Input::isPost()){
            $_POST=Input::post();
            $ret = $Admin->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,'添加成功');
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $this->assign(array(
            '_GET'=>$_GET,
            'status'=>$Admin->status,
            'is_shangjia'=>$Admin->is_shangjia,
        ))->base('zuojia_shop/product_categroy_edit');
    }

    /**
     * 项目卡密
     */
    public function c_card_list(){
        $pid = Input::request('pid',0,'intval');
        $model = new Card_No();
        $Project = new Project();
        $pinfo = $Project->findOne(array('id'=>$pid));
        //print_r($pinfo);
        $cond = array();
        if($pid>0){
            $cond['project_id']=$pid;
        }
        $list = $model->findall($cond);
        $this->assign(array(
            'pinfo'=>$pinfo,
            '_GET'=>$_GET,
            'shua_pingtai'=>$this->shua_pingtai,
            'list'=>$list,
        ))->base();
    }

    /**
     * 项目卡密
     */
    public function c_jifen_rule(){
        $pid = Input::request('pid',0,'intval');
        $model = new Jifen_Rule();
        $Project = new Project();
        $pinfo = $Project->findOne(array('id'=>$pid));

        $cond = array();
        if($pid>0){
            $cond['project_id']=$pid;
        }
        $list = $model->findall($cond);
        //print_r($list);
        $this->assign(array(
            'pinfo'=>$pinfo,
            '_GET'=>$_GET,
            'shua_pingtai'=>$this->shua_pingtai,
            'list'=>$list,
        ))->base();
    }



    /**
     * 业务积分规则添加
     */
    public function c_jifen_rule_add(){
        $pid = Input::request('pid',0,'intval');

        $Jifen_Rule = new Jifen_Rule();
        $Project = new Project();
        $pinfo = $Project->findOne(array('id'=>$pid));

        if(Input::isAJAX()){
            $_POST = Input::post();
            $_POST['create_time']=time();
            $id = $Jifen_Rule->add($_POST);
            if($id['ret'] > 0 ){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),1004);
        }
        $this->assign(array(
            'pinfo'=>$pinfo,
            'shua_pingtai'=>$Project->getShuaPingtai(),
            'status'=>$Jifen_Rule->getStatus()
        ))->base();
    }
    /**
     * 修改卡密，通过业务id
     */
    public function c_jifen_rule_edit(){
        $pid = Input::request('pid',0,'intval');
        $id = Input::request('id',0,'intval');

        $Jifen_Rule = new Jifen_Rule();
        $Project = new Project();
        $pinfo = $Project->findOne(array('id'=>$pid));
        if(Input::isAJAX()){
            $_POST = Input::post();
            $_POST['update_time']=time();
            $id = $Jifen_Rule->edit($_POST);
            if($id['ret'] > 0 ){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),1005);
        }
        $info = $Jifen_Rule->findOne(array('id'=>$id));
        $this->assign(array(
            'info'=>$info,
            'pinfo'=>$pinfo,
            'shua_pingtai'=>$Project->getShuaPingtai(),
            'status'=>$Jifen_Rule->getStatus()
        ))->base('project/jifen_rule_add');
    }
}