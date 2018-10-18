<?php

namespace Mabe\RateLimitBundle\Tests;


use Mabe\RateLimitBundle\MabeRateLimitBundle;
use Mabe\RateLimitBundle\DependencyInjection\MabeRateLimitExtension;
use PHPUnit\Framework\TestCase;

class MabeRateLimitBundleTest extends TestCase
{
    public function testGetContainerExtension()
    {
        $bundle = new MabeRateLimitBundle();
        $this->assertInstanceOf(MabeRateLimitExtension::class, $bundle->getContainerExtension());
    }
}