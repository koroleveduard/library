<?php

namespace LibraryTest\Cache;

use Library\Cache\MemCacheServer;
use PHPUnit\Framework\TestCase;

class MemCacheServerTest extends TestCase
{
    protected $server;
    protected $options;

    public function setUp()
    {
        $this->options = [
            'host' => '127.0.0.1',
            'port' => 11211,
        ];

        $this->server = new MemCacheServer($this->options);
    }

    public function testGetHost()
    {
        $host = $this->options['host'];
        $server = $this->server;
        $this->assertEquals($host, $server->host);
    }

    public function testGetPort()
    {
        $port = $this->options['port'];
        $server = $this->server;
        $this->assertEquals($port, $server->port);
    }
}