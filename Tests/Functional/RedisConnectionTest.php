<?php

namespace Mabe\RateLimitBundle\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedisConnectionTest extends WebTestCase
{
    public function testDefaultRedisConnection()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $pong = $redis->ping();
        $this->assertEquals("+PONG", $pong);
    }
}