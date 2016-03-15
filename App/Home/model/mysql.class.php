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

namespace home\model;

use pulgins\db as db;

class mysql extends Db{

    public function test(){

        $d = new Db();
        $d->useConfig();
        return $d->select('dns_admin');
    }
}