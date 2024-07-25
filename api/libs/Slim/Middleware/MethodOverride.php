<?php

namespace Slim\Middleware;

class MethodOverride extends \Slim\Middleware
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     * @param  array  $settings
     */
    public function __construct($settings = array())
    {
        $this->settings = array_merge(array('key' => '_METHOD'), $settings);
    }

    public function call()
    {
        $env = $this->app->environment();
        if (isset($env['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            // Header commonly used by Backbone.js and others
            $env['slim.method_override.original_method'] = $env['REQUEST_METHOD'];
            $env['REQUEST_METHOD'] = strtoupper($env['HTTP_X_HTTP_METHOD_OVERRIDE']);
        } elseif (isset($env['REQUEST_METHOD']) && $env['REQUEST_METHOD'] === 'POST') {
            // HTML Form Override
            $req = new \Slim\Http\Request($env);
            $method = $req->post($this->settings['key']);
            if ($method) {
                $env['slim.method_override.original_method'] = $env['REQUEST_METHOD'];
                $env['REQUEST_METHOD'] = strtoupper($method);
            }
        }
        $this->next->call();
    }
}
