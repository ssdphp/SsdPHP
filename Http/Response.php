<?php
namespace SsdPHP\Http;

use SsdPHP\Core\Config;
use SsdPHP\Core\Language;

class Response
{
    // 输出数据的转换方法
    protected static $tramsform = null;
    // 输出数据的类型
    protected static $type = '';
    // 输出数据
    protected static $data = '';
    // 是否exit
    protected static $isExit = false;

    /**
     * 发送数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回数据格式
     * @param bool $return 是否返回数据
     * @return mixed
     */
    public static function send($data = '', $type = '', $return = false)
    {
        $type = strtolower($type ?: self::$type);

        $headers = [
            'json'   => 'application/json',
            'xml'    => 'text/xml',
            'html'   => 'text/html',
            'jsonp'  => 'application/javascript',
            'script' => 'application/javascript',
            'text'   => 'text/plain',
        ];

        if (!headers_sent() && isset($headers[$type])) {
            header('Content-Type:' . $headers[$type] . '; charset=utf-8');
        }

        $data = $data ?: self::$data;
        switch ($type) {
            case 'json':
                // 返回JSON数据格式到客户端 包含状态信息
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            case 'jsonp':
                // 返回JSON数据格式到客户端 包含状态信息
                $handler = Input::get(Config::get('datatype',"callback"));
                $data    = $handler . '(' . json_encode($data, JSON_UNESCAPED_UNICODE) . ');';
                //exit($handler.'('.json_encode($data).');');
                break;
            case '':
            case 'html':
            case 'text':
                // 不做处理
                break;
            default: /* todo 其他扩展*/;
        }

        if ($return) {
            return $data;
        }
        echo $data;
        exit();
    }

    /**
     * 用于客户端api数据返回
     * @param array $data
     * @param int $code
     * @param null $langCode
     * @param string $type
     * @param bool $return
     * @return array|mixed
     */
    public static function apiJsonResult($data=array(),$code=1,$langCode=null,$type='json',$return=false){

        $sendData = array();
        $sendData['code'] = intval($code);
        $type = !empty($type) ? $type : 'json';

        if(is_string($langCode) && !is_numeric($langCode)){
            $sendData['code_str']=$langCode;
        }else{
            $sendData['code_str']  = !empty($langCode) && $langCode>0 ? Language::get($langCode,"Invalid Code!"):Language::get($sendData['code'],"unknown error!");
        }
        $sendData['data'] = !empty($data) ? $data:new \stdClass();
        $data = self::send($sendData,$type,true);
        self::data($data);
        if ($return) {
            return $data;
        }
        exit($data);
    }

    /**
     * 输出数据设置
     * @access public
     * @param mixed $data 输出数据
     * @return void
     */
    public static function data($data)
    {
        self::$data = $data;

    }

    public static function getData(){
        return self::$data;
    }
    /**
     * URL重定向
     * @access protected
     * @param string $url 跳转的URL表达式
     * @param array|int $params 其它URL参数或http code
     * @return void
     */
    public static function redirect($url, $params = [])
    {
        $http_response_code = 301;
        if (in_array($params, [301, 302])) {
            $http_response_code = $params;
            $params             = [];
        }
        $url = preg_match('/^(https?:|\/)/', $url) ? $url : Url::build($url, $params);
        header('Location: ' . $url, true, $http_response_code);
    }

    /**
     * 设置响应头
     * @access protected
     * @param string $name 参数名
     * @param string $value 参数值
     * @return void
     */
    public static function header($name, $value)
    {
        header($name . ':' . $value);
    }

}

