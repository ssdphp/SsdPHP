
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>谢谢你访问访问www.ssdphp.com</title>
    <style>
        a{  text-decoration:none;  }  a:hover{  text-decoration:underline;  }
    </style>
</head>
<body>
<b>参考SlightPHP Thinkphp zphp 设计思路</b>
<ul>
    <li>1.composer require "ssdphp/ssdphp:dev-master"</li>
    <li>2.mv App to rootpath</li>
    <li>3.mv config to rootpath</li>
</ul>
<br>
<pre>
<h2>nginx配置</h2>
server {
	listen       80;
	server_name  www.ssdphp.com;
	#charset koi8-r;
	root /mnt/hgfs/www/ssdphp.com/vendor/ssdphp/ssdphp/www;
	location / {
		index  index.html index.htm index.php;
		if (!-e $request_filename) {
			rewrite ^/(.*)  /index.php?PATH_INFO=$1 last;
		}
	}
	error_page  404              /404.html;
	error_page   500 502 503 504  /50x.html;
	location = /50x.html {
		root   html;
	}
	location ~ \.php$ {
		fastcgi_pass   127.0.0.1:9000;
		fastcgi_index  index.php;
		fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
		include        fastcgi_params;
	}
	location ~ /\.ht {
		deny  all;
	}
}
<h2>目录结构：</h2>
+App
    +Home
        +controller
        +model
        +templates
        +templates_c
        +templates_config
    +Task
    +...
+config
+vendor
    +composer
    +ssdphp
        +ssdphp
            +SsdPHP
                +Core
                +Pulgins
                +...
            +www
<h2>Cli</h2>
    php /mnt/hgfs/www/cqzkjz.com/vendor/ssdphp/ssdphp/www/index.php /home/index/index

</pre>
</body>
</html>