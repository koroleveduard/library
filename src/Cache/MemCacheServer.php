<?php

namespace Library\Cache;

use Zend\Stdlib\AbstractOptions;

class MemCacheServer extends AbstractOptions
{

    public $host;

    public $port = 11211;

    public $weight = 1;

    public $persistent = true;

    public $timeout = 1000;

    public $retryInterval = 15;

    public $status = true;

    public $failureCallback;


    public function setHost($host)
    {
        $this->host = $host;
    }


    public function setPort($port)
    {
        $this->port = $port;
    }


    public function setWeight($weight)
    {
        $this->weight = $weight;
    }


    public function setPersistent($persistent)
    {
        $this->persistent = $persistent;
    }


    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }


    public function setRetryInterval($retryInterval)
    {
        $this->retryInterval = $retryInterval;
    }


    public function setStatus($status)
    {
        $this->status = $status;
    }


    public function setFailureCallback($failureCallback)
    {
        $this->failureCallback = $failureCallback;
    }
}