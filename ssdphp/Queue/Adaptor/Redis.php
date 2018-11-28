<?php

namespace SsdPHP\Pulgins\Queue\Adaptor;

use SsdPHP\Pulgins\Queue\IQueue;
use SsdPHP\Pulgins\Cache\Factory as Cache;

class Redis implements IQueue
{
    private $redis;

    public function __construct($config)
    {
        if (empty($this->redis)) {
            $this->redis = Cache::getInstance('Redis');
        }
    }

    public function add($key, $data)
    {
        return $this->redis->rPush($key, $data);
    }

    public function get($key)
    {
        return $this->redis->lPop($key);
    }

    /**
     * 批量取出并清空所有的数据
     * 需最新redis-storage支持
     * @param $key
     * @return mixed
     */
    public function getAll($key)
    {
        return $this->redis->lAll($key);
    }

}