<?php


namespace SsdPHP\DataBase\Adaptor;
/**
 * Class DbData
 * @author xiaohuihui <xzh_tx@163.com>
 * @package SsdPHP\Pulgins\DataBase\Adaptor
 */
class MysqlData{
	/**
	 * @var int
	 */
	var $page=1;
	/**
	 * @var int
	 */
	var $pageSize=0;
	/**
	 * @var int
	 */
	var $limit=0;
	/**
	 * @var int
	 */
	var $totalPage=0;
	/**
	 * @var int
	 */
	var $totalSize=0;
	/**
	 * @var int
	 */
	var $totalSecond=0;
	/**
	 * @var array
	 */
	var $items;//array(array(),array());

}
?>