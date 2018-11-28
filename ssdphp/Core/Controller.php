<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/4
 * Time: 15:22
 */
namespace SsdPHP\Core;

use SsdPHP\View\Factory as View;
use SsdPHP\SsdPHP;

class Controller
{
    private $_View ;

    public function __construct($config=null)
    {
        if(empty($this->_View)){
            $adapter="Tpl";
            if(!empty($config['Adaptor'])) {
                $adapter=$config['Adaptor'];
            }

            $this->_View = View::getInstance($adapter,$config);
        }
    }

    /**
     * @param string $content_tpl
     * @param string $base_tpl
     */
    public function base($content_tpl="",$base_tpl='common/base'){
        if(empty($content_tpl)){
            $content_tpl = strtolower(SsdPHP::getController()."/".SsdPHP::getAction());
        }
        $content = $this->_View->fetch($content_tpl);
        $this->_View->assign('content',$content)->display($base_tpl);
    }

    /**
     * 显示模板
     * @param string $tpl
     */
    public function display($tpl=""){
        if(empty($tpl)){
            $tpl = strtolower(SsdPHP::getController()."/".SsdPHP::getAction());
        }
        $this->_View->display($tpl);
    }

    /**
     * 获取模板内容数据
     * @param string $tpl
     */
    public function fetch($tpl=""){
        if(empty($tpl)){
            $tpl = strtolower(SsdPHP::getController()."/".SsdPHP::getAction());
        }
        return $this->_View->fetch($tpl);
    }

    /**
     * 分配变量
     * @param string|array $tpl_var
     * @param null $value
     * @return $this
     */
    public function assign($tpl_var, $value = null){


        if (is_array($tpl_var)){
            $this->_View->assign($tpl_var);
        } else {
            if ($tpl_var != '')
                $this->_View->assign($tpl_var,$value);
        }
        return $this;
    }
}