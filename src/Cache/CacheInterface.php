<?php

namespace Library\Cache;

interface CacheInterface
{
    public function set($key, $value, $duration = null, $dependency = null);

    public function get($key);

    public function add($key, $value, $duration = 0, $dependency = null);

    public function delete($key);
}