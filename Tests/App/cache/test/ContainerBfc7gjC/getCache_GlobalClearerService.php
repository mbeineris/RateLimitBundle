<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'cache.global_clearer' shared service.

return $this->services['cache.global_clearer'] = new \Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer(array('cache.app' => ($this->services['cache.app'] ?? $this->load('getCache_AppService.php')), 'cache.system' => ($this->services['cache.system'] ?? $this->load('getCache_SystemService.php')), 'cache.annotations' => ($this->privates['cache.annotations'] ?? $this->load('getCache_AnnotationsService.php'))));