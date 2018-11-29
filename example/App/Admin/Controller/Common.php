<?php
/**
 * Created by PhpStorm.
 * User: Young
 * Date: 2016/11/1
 * Time: 下午 17:52
 */

namespace App\Admin\Controller;

use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Auth_Group_Access;
use App\Admin\Model\Admin_Menu;
use SsdPHP\Core\Controller;
use SsdPHP\Http\Input;
use SsdPHP\Http\Response;
use SsdPHP\SsdPHP;
use SsdPHP\Session\Session;

class Common extends Controller
{

    public static $white_list=array();
    public function __construct()
    {
        parent::__construct();
        $currententry = strtolower(SsdPHP::getController()."/".SsdPHP::getAction());
        $adminConfig = Admin::$AdminConfig;
        $adminInfo = Session::get($adminConfig['UserDataKey']);

        if(empty($adminInfo['uid'])){
            if(Input::isAJAX()){
                Response::apiJsonResult(array(), 403);
            }else{
                header("location:/public/login.html");
                exit;
                //$this->display('common/403');
                //exit;
            }
        }
        //权限检查
        $Admin_Auth_Group_Access = new Admin_Auth_Group_Access();
        //权限检测
        $chkRet = $Admin_Auth_Group_Access->AccessCheck($adminInfo['uid']);

        $Admin_Menu = new Admin_Menu();
        $ret =$Admin_Menu->handleAdminMenu($currententry);
        $data=array(
            'admin_menu'=>!empty($ret['admin_menu'])?$ret['admin_menu']:"",
            'nav_url'=>!empty($ret['nav_url'])?$ret['nav_url']:"",
            'AdminInfo'=>$adminInfo,
        );

        $this->assign($data);
        define("UID", $adminInfo['uid']);

        if($chkRet==false && !in_array($currententry,self::$white_list)){
            if(Input::isAJAX()){
                Response::apiJsonResult(array(), 1007);
            }else{
                $this->base('common/access',"common/base");
                exit;
            }
        }
    }
}