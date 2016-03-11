<?php
// +----------------------------------------------------------------------
// | api.uarein.com
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.uarein.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaohuihui <598550105@qq.com> //
// +----------------------------------------------------------------------
namespace pulgins;

use pulgins\db as database,pulgins\Config;
/**
 * @package SlightPHP
 */
class Db extends database\Db{
    /**
     * 构造方法
     * @param string $table_name
     * @return void
     **/
    public function __construct($config=array()){

        if(!empty($config))parent::init($config);
    }
    /**
     * 切换数据库配置文件
     * @param string $zone
     * @param string $type	main|query
     * @return array
     */
    function useConfig($zone="DB",$type=""){
        $config = Config::get($zone);
        if($type==""){
            self::init($config[array_rand($config)]);
        }else{
            self::init($config[$type]);
        }

    }
}
