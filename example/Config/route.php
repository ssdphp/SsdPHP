<?php

return array(
    'route' => array(
        //模块=>配置项
        'home' => array(
            //规则=>对应到真实路径
            '/^\/test(.*)/sm' => 'Home/index/index',
        )
    )
);