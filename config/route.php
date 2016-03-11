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
////路由
return array(
    'ROUTE'=>array(
        'home'=>array(
            //规则=>对应的模型
            '/^\/product\/(\d+?)(.*)/sm'=>'home/index/index/id/$1',
        )
    )
);