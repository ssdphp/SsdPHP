<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


namespace SsdPHP\Session\Adaptor;
use SsdPHP\Cache\Cache as Cache;

class Redis
{
    private $redis;
    private $gcTime = 1800;
    private $config;

    public function __construct($config)
    {
        if (empty($this->redis)) {
            $this->redis = Cache::getInstance();
            if (!empty($config['new_cache_expire'])) {
                $this->gcTime = $config['new_cache_expire'] * 60;
            }
            $this->config = $config;
        }
    }

    public function open($savePath="", $sessionName="")
    {
        return !empty($this->redis)?true:false;
    }

    public function close()
    {
        return true;
    }

    public function gc($time)
    {
        return true;
    }

    public function read($sid="")
    {
        if(!empty($this->config['sid_prefix'])) {
            $sid = $this->config['sid_prefix'].$sid;
        }
        $data = $this->redis->get($sid);
        if (!empty($data)) {
            $this->redis->setTimeout($sid, $this->gcTime);
        }
        return !empty($data)?$data:"";
    }

    public function write($sid="", $data="")
    {
        if(empty($data)) {
            return true;
        }
        if(!empty($this->config['sid_prefix'])) {
            $sid = $this->config['sid_prefix'].$sid;
        }
        return (bool)$this->redis->setex($sid, $this->gcTime, $data);
    }

    public function destroy($sid="")
    {
        if(empty($sid)){
            return false;
        }
        if(!empty($this->config['sid_prefix'])) {
            $sid = $this->config['sid_prefix'].$sid;
        }
        return (bool)$this->redis->delete($sid);
    }

    public function create_sid(){
        return "";
    }
}
