<?php

namespace Mabe\RateLimitBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Redis;

class RateLimitListener implements EventSubscriberInterface
{
    private $storage;
    public $pathRules;
    private $tokenStorage;
    private $authorizationChecker;
    private $enabled;
    private $clientIp;

    /**
     * RateLimitListener constructor.
     * @param $pathRules
     * @param TokenStorage $tokenStorage
     * @param AuthorizationChecker $authorizationChecker
     * @param $storage
     * @param $enabled
     */
    public function __construct(
        $pathRules,
        TokenStorage $tokenStorage,
        AuthorizationChecker $authorizationChecker,
        Redis $storage,
        $enabled
    )
    {
        $this->pathRules = $pathRules;
        $this->tokenStorage = $tokenStorage;
        $this->storage = $storage;
        $this->authorizationChecker = $authorizationChecker;
        $this->enabled = $enabled;
    }

    /**
     * @param GetResponseEvent $event
     * @return array|false|string
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->enabled) {
            return;
        }

        $redisClient = $this->storage;
        $requestRoute = $event->getRequest()->getPathInfo();
        $this->clientIp = $event->getRequest()->getClientIp();

        if (!$event->isMasterRequest()) {
            return;
        }

        foreach ($this->pathRules as $rule) {

            $limitedRoute = $rule['path'];
            $key = null;

            if ($requestRoute === $limitedRoute) {
                switch ($rule['identifier']):
                    case 'ip':
                        $key = $this->createKey($limitedRoute, null, true);
                        break;
                    case 'username':
                        $token = $this->tokenStorage->getToken();
                        if ($token && $this->authorizationChecker->isGranted('ROLE_USER')) {
                            $key = $this->createKey($limitedRoute, $token->getUsername(), false);
                        }
                        else {
                            $key = $this->createKey($limitedRoute, null, true);
                        }
                        break;
                endswitch;
                $redisClient->incr($key);
                $redisClient->setTimeout($key, $rule['period']);

                if ($redisClient->get($key) > $rule['limit']) {
                    throw new HttpException(429, 'Too many requests');
                }
            }
        }

        return;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => ['onKernelRequest', 256],
        );
    }

    /**
     * Creates redis key based on identifier
     *
     * @param $limitedRoute
     * @param null $identifier
     * @param bool $addIp
     * @return string
     */
    private function createKey($limitedRoute, $identifier = null, $addIp = false) {

        $key = "route:".$limitedRoute." identifier:";

        if (!empty($identifier)) {
            $key .= $identifier;
        }

        if ($addIp) {
            $key .= $this->clientIp;
        }

        return $key;
    }
}
