<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


namespace SsdPHP\Pulgins\Session\Adaptor;

use SsdPHP\SsdPHP;
use SsdPHP\Pulgins\File\Dir;

class File
{
    private $gcTime = 1800;
    private $config;
    private $filename;

    public function __construct($config)
    {
        if (!empty($config['new_cache_expire'])) {
            $this->gcTime = $config['new_cache_expire'] * 60;
        }
        $this->config = $config;
    }

    public function open($path, $sid)
    {
        $this->filename = $this->getFileName($path, $sid);
        return !empty($this->filename) ? true : false;
    }

    public function close()
    {
        return true;
    }

    public function gc($time)
    {
        $path = $this->getPath();
        $files = Dir::tree($path);
        foreach($files as $file) {
            if(false !==strpos($file, 'sess_')) {
                if(fileatime($file) < (time() - $this->gcTime)) {
                    unlink($file);
                }
            }
        }
        return true;
    }

    public function read($sid)
    {


        $this->filename = $this->getFileName($sid);
        if (is_file($this->filename)) {
            $content = file_get_contents($this->filename);
            if (strlen($content) < 10) {
                unlink($this->filename);
                return "";
            }
            $time = floatval(substr($content, 0, 10));
            if ($time < (time() - $this->gcTime)) {
                unlink($this->filename);
                return "";
            }
            return substr($content, 10)."";
        }
        return "";
    }

    public function write($sid, $data)
    {
        $this->filename = $this->getFileName($sid);
        $content = time() + $this->gcTime . $data;
        file_put_contents($this->filename, $content);
        return true;
    }

    public function destroy($sid)
    {
        $this->filename = $this->getFileName($sid);
        if (is_file($this->filename)) {
            unlink($this->filename);
            return false;
        }
    }

    private function getPath()
    {
        return !empty($this->config['save_path']) ? $this->config['save_path'] : SsdPHP::getRootPath() . 'session_tmp';
    }

    private function getFileName($sid)
    {
        $path = $this->getPath();
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if(!empty($this->config['callback']) && is_callable($this->config['callback'])) {
            return call_user_func($this->config['callback'], $path, $sid);
        }

        return $path . DIRECTORY_SEPARATOR . 'sess_' . $sid;
    }
}
