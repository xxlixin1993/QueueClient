<?php

/**
 * Interface Queue
 */
namespace queue\interfaces;

interface IQueue
{
    public function driver();
    
    public function publish($subject, $payload = null, $inbox = null);
    
    public function close();

    public function subscriber($subject, \Closure $callback);
    
    public function wait($timeout);
}