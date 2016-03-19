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
//视图配置
return array(

    'View'=>array(

        'force_compile'=>true,
        'debugging'=>false,
        'caching'=>true,
        'cache_lifetime'=>120,
        'tpl_suffix'=>".html",
        'Adaptor'=>"Tpl",//自带的Tpl，和Smarty
    )
);