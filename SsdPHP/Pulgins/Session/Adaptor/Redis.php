<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


namespace SsdPHP\Pulgins\Session\Adaptor;
use SsdPHP\Pulgins\Cache\Factory as Cache;

class Redis
{
    private $redis;
    private $gcTime = 1800;
    private $config;

    public function __construct($config)
    {
        if (empty($this->redis)) {
            $this->redis = Cache::getInstance('Redis');
            if (!empty($config['new_cache_expire'])) {
                $this->gcTime = $config['new_cache_expire'] * 60;
            }
            $this->config = $config;
        }
    }

    public function open($path, $sid)
    {
        return !empty($this->redis);
    }

    public function close()
    {
        return true;
    }

    public function gc($time)
    {
        return true;
    }

    public function read($sid)
    {
        if(!empty($this->config['sid_prefix'])) {
            $sid = str_replace($this->config['sid_prefix'], '', $sid);
        }
        $data = $this->redis->get($sid);
        if (!empty($data)) {
            $this->redis->setTimeout($sid, $this->gcTime);
        }
        return $data;
    }

    public function write($sid, $data)
    {
        if(empty($data)) {
            return true;
        }
        if(!empty($this->config['sid_prefix'])) {
            $sid = str_replace($this->config['sid_prefix'], '', $sid);
        }
        return $this->redis->setex($sid, $this->gcTime, $data);
    }

    public function destroy($sid)
    {
        if(!empty($this->config['sid_prefix'])) {
            $sid = str_replace($this->config['sid_prefix'], '', $sid);
        }
        return $this->redis->delete($sid);
    }
}
