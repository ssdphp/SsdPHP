<?php

namespace SsdPHP\Page;

use SsdPHP\Core\Config;
use SsdPHP\SsdPHP;


class Factory{
    public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows; // 总行数
    public $totalPages; // 分页总页面数
    public $rollPage   = 11;// 分页栏每页显示的页数
	public $lastSuffix = true; // 最后一页是否显示总页数

    private $p       = 'page'; //分页参数名
    private $url     = ''; //当前链接URL
    private $nowPage = 1;

	// 分页显示定制
    private $config  = array(
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev'   => '上一页',
        'next'   => '下一页',
        'first'  => '1...',
        'last'   => '...%TOTAL_PAGE%',
        'theme'  => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
    );

    /**
     * 架构函数
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows, $listRows=20, $parameter = array()) {
        Config::get('VAR_PAGE') && $this->p = Config::get('VAR_PAGE'); //设置分页参数名称
        /* 基础设置 */
        $this->totalRows  = $totalRows; //设置总记录数
        $this->listRows   = $listRows;  //设置每页显示行数
        $this->parameter  = empty($parameter) ? $_GET : $parameter;
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);
    }

    /**
     * 定制分页链接设置
     * @param string $name  设置名称
     * @param string $value 设置值
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }

    /**
     * 手机分页
     * @return string
     */
    public function mshow(){

        if(0 == $this->totalRows) return '';
        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        /*echo 1234;
        if($this->totalPages == 1){
            return '';
        }
        echo 123;*/
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && ($this->config['last'] = $this->totalPages);
        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ?
            '<a id="prev" href="' . $this->url($up_row) . '">上一页</a>'
            :
            '<a id="prev" class="disabled" href="javascript:;">上一页</a>';

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ?
            '<a id="next" href="' . $this->url($down_row) . '">下一页</a>'
            :
            '<a id="next" href="javascript:;" class="disabled">下一页</a>';

        return "
            $up_page
            <span>{$this->nowPage}/{$this->totalPages}<span>
            $down_page
        ";
    }/**
     * 手机分页2
     * @return string
     */
    public function m2show(){

        if(0 == $this->totalRows) return '';
        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        /*echo 1234;
        if($this->totalPages == 1){
            return '';
        }
        echo 123;*/
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && ($this->config['last'] = $this->totalPages);
        //上一页 <li class="previous"><a href="exchange_history_1425469807.php?ref_id=40104&p=1" target="_parent">上一页</a></li>
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ?
            //'<a id="prev" href="' . $this->url($up_row) . '">上一页</a>'
            '<li class="previous">
                <a id="prev" href="' . $this->url($up_row) . '">上一页</a>
            </li>'
            :
            '<li class="previous"><a class="disabled" href="javascript:;">上一页</a></li>';


        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ?
            '<li class="next"><a id="next" href="' . $this->url($down_row) . '">下一页</a></li>'
            :
            '<li class="next"><a id="next" href="javascript:;" class="disabled">下一页</a></li>';

        return "
            $up_page
            <span>{$this->nowPage}/{$this->totalPages}<span>
            $down_page
        ";
    }



    /**
     * amziui组装分页链接
     * @return string
     */
    public function am_show() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $url = SsdPHP::getController()."/".SsdPHP::getAction();
        //$this->url = $this->U($url, $this->parameter);
        //$this->url = "/idex/index".$this->parameter;
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数

        if($this->totalPages == 1){
            return '';
        }

        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && ($this->config['last'] = $this->totalPages);

        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ?
            '<li class="am-pagination-prev"><a href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a></li>'
            :
            '<li class="am-disabled"><a href="javascript:;">' . $this->config['prev'] . '</a></li>';

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ?
            '<li class="am-pagination-next"><a href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a></li>'
            :
            '<li class="am-disabled"><a href="javascript:;">' . $this->config['next'] . '</a></li>';

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first = '<li class="am-pagination-first"><a href="' . $this->url(1) . '">' . $this->config['first'] . '</a></li>';
        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = '<li class="am-pagination-last"><a href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a></li>';
        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
            if(($this->nowPage - $now_cool_page) <= 0 ){
                $page = $i;
            }elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
                $page = $this->totalPages - $this->rollPage + $i;
            }else{
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<li><a href="' . $this->url($page) . '">' . $page . '</a></li>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= '<li class="am-active"><a href="javascript:;"  class="am-active">' . $page . '</a></li>';
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
            $this->config['theme']);
        return "{$page_str}";

    }
    /**
     * bootcss组装分页链接
     * @return string
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $url = SsdPHP::getController()."/".SsdPHP::getAction();
        $this->url = $this->U($url, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数

        if($this->totalPages == 1){
            return '';
        }

        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && ($this->config['last'] = $this->totalPages);

        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ?
            '<li class="paginate_button previous"><a href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a></li>'
            :
            '<li class="paginate_button previous disabled"><a href="javascript:;">' . $this->config['prev'] . '</a></li>';

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ?
            '<li class="paginate_button next"><a href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a></li>'
            :
            '<li class="paginate_button next disabled"><a href="javascript:;">' . $this->config['next'] . '</a></li>';

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1 || true){
            $the_first = '<li class="paginate_button"><a href="' . $this->url(1) . '">' . $this->config['first'] . '</a></li>';
        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = '<li class="paginate_button"><a href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a></li>';
        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
            if(($this->nowPage - $now_cool_page) <= 0 ){
                $page = $i;
            }elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
                $page = $this->totalPages - $this->rollPage + $i;
            }else{
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<li class="paginate_button"><a href="' . $this->url($page) . '">' . $page . '</a></li>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= '<li class="paginate_button active"><a href="javascript:;">' . $page . '</a></li>';
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
            $this->config['theme']);
        return "{$page_str}";

    }
    /**
     * URL组装 支持不同URL模式
     * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
     * @param string|array $vars 传入的参数，支持数组和字符串
     * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
     * @param boolean $domain 是否显示域名
     * @return string
     */
    function U($url='',$vars='',$suffix=true,$domain=false) {
        // 解析URL
        $info   =  parse_url($url);
        $murl = SsdPHP::getController()."/".SsdPHP::getAction();

        // 解析参数
        if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
            parse_str($vars,$vars);
        }elseif(!is_array($vars)){
            $vars = array();
        }
        if(isset($info['query'])) { // 解析地址里面参数 合并到vars
            parse_str($info['query'],$params);
            $vars = array_merge($params,$vars);
        }
        $urlCase    =   Config::get('URL_CASE_INSENSITIVE',true);

        $url        =   "/".$murl."?";
        if($urlCase){
            $url    =   strtolower($url);
        }
        if(!empty($vars)) {
            $vars   =   http_build_query($vars);
            $url   .=   $vars;
        }

        if(isset($anchor)){
            $url  .= '#'.$anchor;
        }
        if($domain) {
            $url   =  ($this->is_ssl()?'https://':'http://').$domain.$url;
        }
        return $url;
    }

    /**
     * 判断是否SSL协议
     * @return boolean
     */
    function is_ssl() {
        if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
            return true;
        }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
            return true;
        }
        return false;
    }
}
