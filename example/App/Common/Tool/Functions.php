<?php
namespace App\Common\Tool ;

use App\Admin\Model\Software;
use SsdPHP\Core\Config;
use SsdPHP\Pulgins\PushBaiduNew\PushSDK;
use SsdPHP\SsdPHP;

class Functions {


    public static function http_type(){
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type;
    }


    /**
     * debug
     * @param $data
     * @param bool $isfile
     */
    public static function debug($data,$isfile = false){
        $date =date("Y-m-d H:i:s");
        if(is_array($data)){
            $data = print_r($data,true);
        }
        file_put_contents(SsdPHP::getRootPath()."/www/debug.txt","========={$date}=========\r\n".$data."\r\n\r\n",FILE_APPEND);
    }
    /**
     * author xiaohuihui <xzh_tx@163.com>
     * @param $url 请求的url
     * @param $option 发送的字段
     * @param int|array $header 发送的头信息
     * @param string $type 请求方法，默认post
     * @return mixed | array
     */
    public static function RequestCurl(string $url, $options=null, $header = [], $type = 'POST') {

        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE ); // 对认证证书来源的检查
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE ); // 从证书中检查SSL加密算法是否存在
        if (! empty ( $options )) {

            curl_setopt ( $curl, CURLOPT_POSTFIELDS, $options ); // Post提交的数据包
        }
        if(empty($header)){
            $header=array(
                'Cache-Control' => 'no-cache',
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
                "content-type: application/x-www-form-urlencoded; charset=UTF-8",
                "user-agent: User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36",
            );
        }
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 15 ); // 设置超时限制防止死循环
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header ); // 设置HTTP头
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        curl_setopt ( $curl, CURLOPT_CUSTOMREQUEST, strtoupper($type) );
        $result = curl_exec ( $curl ); // 执行操作
        $httpCode=curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close ( $curl ); // 关闭CURL会话

        return $result;
    }

    /**
     * 返回IP地址
     * @return array|false|string
     */
    public static function getIP()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');

        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }



    /**
     * 生成订单id
     * @return string
     */
    public static function OrderId($prefix="order"){
        return "$prefix".date("YmdHis").mt_rand(100000,999999);
    }


    /**
     * @param $data
     * @param string $sign
     * @param string $checkmd5
     * @return bool
     */
    public static function CheckSign(array $data,$checkmd5="md5"){
        if(empty($data) || empty($data['sign'])){
            return false;
        }
        if(isset($data['sign'])){
            $sign = $data['sign'];
            unset($data['sign']);
        }else{
            return false;
        }

        ksort($data);
        $str = md5(http_build_query($data).($checkmd5));

        return $sign===$str;
    }


    public static function opensslEncrypt($sStr, $sKey="skey", $method = 'AES-128-ECB'){
        $str = openssl_encrypt($sStr,$method,$sKey);
        return $str;
    }
    public static function opensslDecrypt($sStr, $sKey="skey", $method = 'AES-128-ECB'){
        $str = openssl_decrypt($sStr,$method,$sKey);
        return $str;
    }


    public static function uniqidReal($lenght = 16) {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            return false;
        }
        return strtoupper(substr(bin2hex($bytes), 0, $lenght));
    }
}
?>