<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SsdPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2015-2020. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.SsdPHP.com                                    |
+-----------------------------------------------------------------------+
}}}*/
namespace pulgins;
use SsdPHP\SsdPHP;
require_once dirname(__FILE__).'/smarty/libs/Smarty.class.php';

class Tpl extends \Smarty{

    /**
     * render a .tpl
     */
    public function render($tpl,$parames=array()){
        $appdir = SsdPHP::getAppDir();
        $model = SsdPHP::getModel();
        $templates_c = $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_c";
        $template_dir= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates";
        $templates_config= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_config";
        $templates_plugins= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_plugins";
        $this->setTemplateDir($template_dir);
        $this->setCompileDir($templates_c);
        $this->setCacheDir($templates_config);
        $this->getPluginsDir($templates_plugins);
        //$smarty->force_compile = true;
        $this->debugging = false;
        $this->caching = false;
        $this->cache_lifetime = 120;
        $this->assign($parames);
        return $this->fetch("$tpl");

    }

    /**
     * 获取模版变量
     * author xiaohuihui <xzh_tx@163.com>
     * @param string $name
     * @return mixed
     */
    public function get($name=""){
        $tpl_var = $this->getTemplateVars();
        if(isset($tpl_var[$name])){
            return $tpl_var[$name];
        }
    }
    /**
     * 302 redirect
     */
    public function redirect($url) {
        header('Location:'.$url);
        exit;
    }
}
?>
