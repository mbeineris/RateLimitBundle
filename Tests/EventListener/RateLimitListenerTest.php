<?php

namespace Mabe\RateLimitBundle\Tests\EventListener;


use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Mabe\RateLimitBundle\EventListener\RateLimitListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;


class RateLimitListenerTest extends WebTestCase
{
    private $client;
    private $listener, $dispatcher, $request;
    private $limit = 5;

    protected function setUp()
    {
        $this->client = static::createClient();

        $paths = array(
            array('path' => '/api/test', 'limit' => $this->limit, 'period' => 10, 'identifier' => 'ip'),
        );

        $redisClient = $this->client->getContainer()->get('mabe_rate_limit.redis_client');
        $redisClient->flushall();

        $this->listener = new RateLimitListener(
            $paths,
            $this->client->getContainer()->get('security.token_storage'),
            $this->client->getContainer()->get('security.authorization_checker'),
            $redisClient,
            true
        );
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->listener);
        $this->request = Request::create('/api/test');
    }

    private function createEvent()
    {
        return new GetResponseEvent(
            $this->client->getKernel(),
            $this->request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }

    public function testValidRequest()
    {
        $this->dispatcher->dispatch(
            KernelEvents::REQUEST,
            $this->createEvent()
        );
        $this->assertTrue(true);
    }

    public function testSpamRequest()
    {
        $this->expectException(HttpException::class);
        for ($i = 0; $i <= $this->limit; $i++) {
            $this->dispatcher->dispatch(
                KernelEvents::REQUEST,
                $this->createEvent()
            );
        }
        $this->assertTrue(true);
    }
}