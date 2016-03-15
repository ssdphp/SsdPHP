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

namespace home\controller;
use common\controller\controller,
    home\model\mysql;
use SsdPHP\SsdPHP;

class pic extends controller{


    public function pic(){
        $pic = SsdPHP::getAppDir()."/../www/1.jpg";

        $fp = fopen($pic,"rb");
        $contents = fread($fp, filesize($pic));
        echo $contents;
        fclose($fp); // close the file

    }

}