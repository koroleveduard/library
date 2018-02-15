<?php

namespace Library\Cache;

class Memcache implements CacheInterface
{
    public $useMemcached = false;

    private $cache;

    private $servers = [];

    public function __construct()
    {
        $this->init();
    }

    public function getServers()
    {
        return $this->servers;
    }

    public function setServers($config)
    {
        foreach ($config as $c) {
            $this->servers[] = new MemCacheServer($c);
        }
    }

    public function set($key, $value, $duration = null, $dependency = null)
    {
        $expire = $duration > 0 ? $duration + time() : 0;

        if ($this->useMemcached) {
            return $this->cache->set($key, $value, $expire);
        }

        return $this->cache->set($key, $value, 0, $expire);
    }

    public function get($key)
    {
        return $this->cache->get($key);
    }

    public function add($key, $value, $duration = 0, $dependency = null)
    {
        $expire = $duration > 0 ? $duration + time() : 0;

        return $this->useMemcached ? $this->cache->add($key, $value, $expire) : $this->cache->add($key, $value, 0, $expire);
    }

    public function delete($key)
    {
        return $this->cache->delete($key, 0);
    }

    protected function init()
    {
        $this->addServers($this->getMemcache(), $this->getServers());
    }

    protected function getMemcache()
    {
        if ($this->cache === null) {
            $extension = $this->useMemcached ? 'memcached' : 'memcache';
            if (!extension_loaded($extension)) {
                throw new \RuntimeException("MemCache requires PHP $extension extension to be loaded.");
            }

            if ($this->useMemcached) {
                $this->cache = new \Memcached();
            } else {
                $this->cache = new \Memcache();
            }
        }

        return $this->cache;
    }

    protected function addServers($cache, $servers)
    {
        if (empty($servers)) {
            $servers = [new MemCacheServer([
                'host' => '127.0.0.1',
                'port' => 11211,
            ])];
        } else {
            foreach ($servers as $server) {
                if ($server->host === null) {
                    throw new \RuntimeException("The 'host' property must be specified for every memcache server.");
                }
            }
        }
        if ($this->useMemcached) {
            $this->addMemcachedServers($cache, $servers);
        } else {
            $this->addMemcacheServers($cache, $servers);
        }
    }

    protected function addMemcachedServers($cache, $servers)
    {
        foreach ($servers as $server) {
            if (empty($existingServers) || !isset($existingServers[$server->host . ':' . $server->port])) {
                $cache->addServer($server->host, $server->port, $server->weight);
            }
        }
    }


    protected function addMemcacheServers($cache, $servers)
    {
        $class = new \ReflectionClass($cache);
        $paramCount = $class->getMethod('addServer')->getNumberOfParameters();
        foreach ($servers as $server) {
            // $timeout is used for memcache versions that do not have $timeoutms parameter
            $timeout = (int) ($server->timeout / 1000) + (($server->timeout % 1000 > 0) ? 1 : 0);
            if ($paramCount === 9) {
                $cache->addserver(
                    $server->host,
                    $server->port,
                    $server->persistent,
                    $server->weight,
                    $timeout,
                    $server->retryInterval,
                    $server->status,
                    $server->failureCallback,
                    $server->timeout
                );
            } else {
                $cache->addserver(
                    $server->host,
                    $server->port,
                    $server->persistent,
                    $server->weight,
                    $timeout,
                    $server->retryInterval,
                    $server->status,
                    $server->failureCallback
                );
            }
        }
    }

}