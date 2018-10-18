<?php

namespace Mabe\RateLimitBundle\Tests\DependencyInjection;


use Mabe\RateLimitBundle\DependencyInjection\Configuration;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * ConfigurationTest
 */
class ConfigurationTest extends WebTestCase
{
    /**
     * @var Processor
     */
    private $processor;

    public function setUp()
    {
        $this->processor = new Processor();
    }

    private function getConfigs(array $configArray)
    {
        $configuration = new Configuration();
        return $this->processor->processConfiguration($configuration, array($configArray));
    }

    public function testDisabledConfiguration()
    {
        $configuration = $this->getConfigs(array('enabled' => false));
        $this->assertArrayHasKey('enabled', $configuration);
        $this->assertFalse($configuration['enabled']);
    }

    public function testPathsConfiguration()
    {
        $paths = array(
            array('path' => '/api/login_check', 'limit' => 10, 'period' => 10)
        );

        $configuration = $this->getConfigs(array('paths' => $paths));

        $this->assertArrayHasKey('paths', $configuration);
        $this->assertEquals($paths, $configuration['paths']);
    }

    public function testMultiplePathsConfiguration()
    {
        $paths = array(
            array('path' => '/api/login_check', 'limit' => 10, 'period' => 10),
            array('path' => '/api2', 'limit' => 1, 'period' => 10),
            array('path' => '/contact', 'limit' => 1, 'period' => 10)
        );

        $configuration = $this->getConfigs(array('paths' => $paths));

        $this->assertArrayHasKey('paths', $configuration);
        $this->assertEquals($paths, $configuration['paths']);
    }
}