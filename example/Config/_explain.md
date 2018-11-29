**项目配置**

所有配置文件名使用小写
配置文件里面以文件名作为key进行配置
如: 
    当前配置文件config.php
    内容：
        <?php 
        return array(
            'config'=>array(
                'option1'=>'value'
            )
        );
        ?>
配置加载。通过Config::load();加载
配置读取。通过Config::get('config');Config::getField('config','option1');
