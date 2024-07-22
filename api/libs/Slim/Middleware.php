<?php

namespace Slim;


abstract class Middleware
{
    /**
     * @var \Slim\Slim Reference to the primary application instance
     */
    protected $app;

    /**
     * @var mixed Reference to the next downstream middleware
     */
    protected $next;

    /**
     * Set application
     *
     * This method injects the primary Slim application instance into
     * this middleware.
     *
     * @param  \Slim\Slim $application
     */
    final public function setApplication($application)
    {
        $this->app = $application;
    }


    final public function getApplication()
    {
        return $this->app;
    }

   ram \Slim|\Slim\Middleware
    
    final public function setNextMiddleware($nextMiddleware)
    {
        $this->next = $nextMiddleware;
    }

  
    final public function getNextMiddleware()
    {
        return $this->next;
    }

   
    abstract public function call();
}
