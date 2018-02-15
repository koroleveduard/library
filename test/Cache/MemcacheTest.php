<?php

namespace LibraryTest\Cache;

use Library\Cache\CacheInterface;
use Library\Cache\Memcache;
use PHPUnit\Framework\TestCase;

class MemcacheTest extends TestCase
{
    /** @var  Memcache */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Memcache();
    }

    public function testShouldImplementsMemcacheInterface()
    {
        $this->assertInstanceOf(CacheInterface::class, $this->instance);
    }

    public function testSaveDataInCache()
    {
        $key = 'key';
        $value = 100;

        $this->instance->set($key, $value);

        $this->assertEquals($value, $this->instance->get($key));
    }
}