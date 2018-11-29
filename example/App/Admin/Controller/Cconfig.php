<?php
namespace App\Admin\Controller;
use App\Admin\Model\Admin;
use App\Admin\Model\Admin_Menu;
use App\Admin\Model\Adv;
use App\Admin\Model\Card_No;
use App\Admin\Model\Jifen_Rule;
use App\Admin\Model\Product;
use App\Admin\Model\Project;
use App\Admin\Model\Software_Version;
use App\Admin\model\SoftwareVersion;
use App\Admin\Model\Video_Product_Catgroy;
use App\Api\Model\Sys_Notice;
use App\Common\Tool\Functions;
use Qiniu\Auth;
use SsdPHP\Core\Config;
use SsdPHP\Http\Input;
use SsdPHP\Http\Response;
use SsdPHP\Page\Factory as Page;
use SsdPHP\SsdPHP;
use SsdPHP\Pulgins\PushBaiduNew\PushSDK;
class Cconfig extends Common {

    /**
     * 卡密列表
     */
    public function c_list(){

        $list = Config::all();
        $this->assign(array('list'=>$list))->base();
    }

    /**
     * 添加卡密，通过业务id
     */
    public function c_card_add(){
        $pid = Input::request('pid',0,'intval');

        $Card_No = new Card_No();
        $Project = new Project();
        $pinfo = $Project->findOne(array('id'=>$pid));

        if(Input::isAJAX()){
            $_POST = Input::post();
            $_POST['create_time']=time();
            $id = $Card_No->add($_POST);
            if($id['ret'] > 0 ){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),1004);
        }
        $this->assign(array(
            'pinfo'=>$pinfo,
            'shua_pingtai'=>$Project->getShuaPingtai(),
            'status'=>$Card_No->getStatus()
        ))->base();
    }
    /**
     * 修改卡密，通过业务id
     */
    public function c_card_edit(){
        $pid = Input::request('pid',0,'intval');
        $id = Input::request('id',0,'intval');

        $Card_No = new Card_No();
        $Project = new Project();
        $pinfo = $Project->findOne(array('id'=>$pid));
        if(Input::isAJAX()){
            $_POST = Input::post();
            $_POST['update_time']=time();
            $id = $Card_No->edit($_POST);
            if($id['ret'] > 0 ){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),1005);
        }
        $info = $Card_No->findOne(array('id'=>$id));
        $this->assign(array(
            'info'=>$info,
            'pinfo'=>$pinfo,
            'shua_pingtai'=>$Project->getShuaPingtai(),
            'status'=>$Card_No->getStatus()
        ))->base('system/card_add');
    }

    /**
     * 上传处理
     */
    public function c_auth_upload(){

        $upload = \SsdPHP\Pulgins\Upload\Factory::getInstance();
        $fileinfo = $upload->uploadOne($_FILES['file']);
        Response::apiJsonResult(array(
            'name'=>$fileinfo['name'],
            'src'=>"/upload/".$fileinfo['savepath'].$fileinfo['savename'],
        ),1);
    }

    /**
     * 广告列表
     */
    public function c_adv(){
        $_GET['page']       = Input::get('page',1,'intval');
        $_GET['pagesize']    = Input::get('pagesize',50,'intval');
        $_GET['pro']    = Input::get('pro',0,'intval');
        $_GET['is_shangjia']    = Input::get('is_shangjia',1,'intval');

        $model = new Adv();
        $Project = new Project();

        $cond = array(
            'project'=>$_GET['pro'],
            'is_shangjia'=>$_GET['is_shangjia']
        );

        $list = $model->getList($cond,$_GET['page'],$_GET['pagesize']);

        $Page = new Page($list->totalSize,$_GET['pagesize']);
        $Product_Category = new Video_Product_Catgroy();
        $this->assign(array(
            'list'=>$list,
            '_GET'=>$_GET,
            'type'=>$model->getType(),
            'is_shangjia'=>$Product_Category->is_shangjia,
            'project'=>$Project->getSoftList(),
            'page'=>$Page->show()
        ))->base();
    }

    private $status=array(
        '1'=>'正常',
        '2'=>'禁用',
    );


    public function c_card_change(){
        $pid = Input::request('pid',0,'intval');
        $card_id = Input::request("card_id",0,'intval');

        $Card_No = new Card_No();
        $time =time();

        $s = $Card_No->_update(['project_id'=>$pid,'use_status'=>1],['use_status'=>0,'update_time'=>$time]);
        $s = $Card_No->_update(['id'=>$card_id,'project_id'=>$pid],['use_status'=>1,'update_time'=>$time]);
        if($s !== false){
            Response::apiJsonResult(array(),1,1006);
        }
        Response::apiJsonResult(array(),1005);
    }

    /**
     * 添加
     */
    public function c_adv_add(){
        $Adv = new Adv();
        $_GET['pro']    = Input::get('pro',0,'intval');
        if(Input::isPost()){

            $_POST=Input::post();
            if(isset($_POST['id'])){
                unset($_POST['id']);
            }
            $ret = $Adv->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $Project = new Project();
        $Product_Category = new Video_Product_Catgroy();
        $this->assign(array(
            'status'=>$this->status,
            '_GET'=>$_GET,
            'pro'=>$_GET['pro'],
            'type'=>$Adv->getType(),
            'is_shangjia'=>$Product_Category->is_shangjia,
            'project'=>$Project->getSoftList(),
        ))->base('system/adv_edit');
    }

    /**
     * 修改
     */
    public function c_adv_edit(){
        $id = Input::get('id');
        $_GET['pro']    = Input::get('pro',0,'intval');
        $model = new Adv();
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
        $Product_Category = new Video_Product_Catgroy();
        $Project = new Project();
        $this->assign(array(
            '_GET'=>$_GET,
            'info'=>$info,
            'pro'=>$_GET['pro'],
            'type'=>$model->getType(),
            'is_shangjia'=>$Product_Category->is_shangjia,
            'project'=>$Project->getSoftList(),
            'status'=>$this->status
        ))->base();
    }

    /**
     * 软件版本管理
     */
    public function c_version(){
        $_GET['page']       = Input::get('page',1,'intval');
        $_GET['pagesize']    = Input::get('pagesize',50,'intval');
        $_GET['pro']    = Input::get('pro',0,'intval');

        $model = new Software_Version();
        $Project = new Project();

        $cond = array(
            'project'=>$_GET['pro']
        );

        $list = $model->getList($cond,$_GET['page'],$_GET['pagesize']);

        $Page = new Page($list->totalSize,$_GET['pagesize']);

        $this->assign(array(
            'list'=>$list,
            '_GET'=>$_GET,
            'is_putaway'=>$model->getIsPutaway(),
            'type'=>$model->getType(),
            'project'=>$Project->getSoftList(),
            'page'=>$Page->show()
        ))->base();
    }
    /**
     * 添加
     */
    public function c_version_add(){
        $Adv = new Adv();
        $_GET['pro']    = Input::get('pro',0,'intval');
        if(Input::isPost()){

            $_POST=Input::post();
            if(isset($_POST['id'])){
                unset($_POST['id']);
            }
            $ret = $Adv->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }

        $this->assign(array(
            'status'=>$this->status,
            '_GET'=>$_GET,
            'pro'=>$_GET['pro'],
            'type'=>$Adv->getType(),
            'project'=>$Adv->getProject(),
        ))->base('system/adv_edit');
    }

    /**
     * 修改
     */
    public function c_version_edit(){
        $id = Input::get('id');
        $_GET['pro']    = Input::request('project',0,'intval');
        $model = new Software_Version();
        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $_POST=Input::post();
            $_POST['updatedAt']=time();
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
            'pro'=>$_GET['pro'],
            'is_putaway'=>$model->getIsPutaway(),
            'type'=>$model->getType(),
            'project'=>$model->getProject(),
            'status'=>$this->status
        ))->base();
    }


    /**
     * 添加
     */
    public function c_product_add(){
        $Adv = new Product();
        if(Input::isPost()){

            $_POST=Input::post();
            if(isset($_POST['id'])){
                unset($_POST['id']);
            }
            $ret = $Adv->add($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1002);
            }
            Response::apiJsonResult(array(),$ret['code']);
            return ;
        }
        $this->assign(array(
            'status'=>$Adv->status
        ))->base('system/product_edit');
    }

    /**
     * 修改
     */
    public function c_product_edit(){
        $id = Input::request('id');
        $model = new Product();
        $info = $model->findOne(array('id'=>$id));
        if(Input::isPost()){
            $_POST=Input::post();
            $_POST['update_time']=time();
            $ret = $model->edit($_POST);
            if($ret['code'] == 1){
                Response::apiJsonResult(array(),1,1003);
            }
            Response::apiJsonResult(array(),$ret['code']);

        }
        $this->assign(array(
            '_GET'=>$_GET,
            'info'=>$info,
            'status'=>$model->status
        ))->base();
    }

    /**
     * 积分充值配置
     */
    public function c_product(){

        $Product = new Product();
        $list = $Product->findall();
        $this->assign(array(
            '_GET'=>$_GET,
            'list'=>$list,
            'status'=>$this->status
        ))->base();
    }


    /**
     * 百度推送
     */
    public function c_baidu_push(){


        if(Input::isAJAX()){
            $msg = Input::request('msg');
            $p = Input::request('p',0,'intval');//项目分类，0-粉丝，1-双击
            $a = Input::request('a',0,'intval');//安卓 0-未选中，1-选择
            $i = Input::request('i',0,'intval');//ios 0-未选中，1-选择

            $s = Functions::BaiduPush($msg,$p,$a,$i,"all",array("msg_type"=>"sys"));
            $Sys_Notice = new Sys_Notice();
            if($a == 1){
                $id = $Sys_Notice->add(array(
                    "p"=>$p,
                    "os"=>1,
                    "content"=>$msg
                ));
            }

            if($i == 1){
                $id = $Sys_Notice->add(array(
                    "p"=>$p,
                    "os"=>2,
                    "content"=>$msg
                ));
            }



            Response::apiJsonResult(array(),1);
        }
        $Project = new Project();
        $this->assign(array(
            "project"=>$Project->getSoftList()
        ))->base();
    }

    /**
     * 粉丝百度推送
     */
    public function c_fans_baidu_push(){
        $this->c_baidu_push();
    }

    /**
     * 七牛上传token
     * @author  xiaohuihui  <xzh_tx@163.com>
     */
    public function c_jstoken(){


        $accessKey = Config::getField("Qiniu","AK");
        $secretKey = Config::getField("Qiniu","SK");
        $auth = new Auth($accessKey, $secretKey);

        // 空间名  http://developer.qiniu.com/docs/v6/api/overview/concepts.html#bucket
        $bucket = Config::getField("Qiniu","bucket");

        $key = Input::request('key',null);
        $key = empty($key) ? null:$key;
        // 生成上传Token
        $token = $auth->uploadToken($bucket,$key,3600*3,array());
        $t = array(
            "uptoken"=>$token,
            "domain"=>Config::getField("Qiniu","domain")['static'],
        );
        $callback = !empty($_GET['callback']) ? $_GET['callback']:"JS_UpTokenGet";

        Response::apiJsonResult($t,1);

    }

    /**
     * 向第三方获取卡密信息
     */
    public function c_card_select(){
        $card_id = Input::request('card_id',0,'intval');
        $Card_No = new Card_No();
        $cardInfo = $Card_No->findOne(array('id'=>$card_id));

        $set_array = array(
            CURLOPT_URL => "http://{$cardInfo['domain']}/getslkminfo.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "workkmKey=".$cardInfo['card_no'],
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
                "Accept-Encoding: gzip, deflate",
                "Accept-Language: zh-CN,zh;q=0.8",
                "Connection: keep-alive",
                "Host: {$cardInfo['domain']}",
                "Origin: http://{$cardInfo['domain']}",
                "Referer: http://{$cardInfo['domain']}/rq_order.html",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
                "X-Requested-With: XMLHttpRequest"
            ),
        );
        $ret = Functions::curl($set_array);
        $r = iconv('gb2312','utf-8',$ret);
        preg_match('/剩余数量: (.*)/',$r,$data);
        $n = isset($data[1])?str_replace(",","",$data[1]):0;
        $id = $Card_No->edit(array(
            'id'=>$card_id,
            'num'=>$n,
            'update_time'=>time()
        ));
        if($id['ret'] > 0 ){
            Response::apiJsonResult(array('num'=>$n),1);
        }
        Response::apiJsonResult(array(),1005);
    }
}