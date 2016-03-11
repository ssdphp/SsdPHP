<?php

namespace SsdPHP\Pulgins\DataBase;

interface ICache
{
    function enable();

    function selectDb($db);

    function add($key, $value, $timeOut);

    function set($key, $value, $timeOut);

    function get($key);

    function delete($key);

    function increment($key, $step = 1);

    function decrement($key, $step = 1);

    function clear();
}