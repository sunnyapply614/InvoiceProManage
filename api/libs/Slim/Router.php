<?php

namespace Slim;

class Router
{
    /**
     * @var Route The current route (most recently dispatched)
     */
    protected $currentRoute;

    /**
     * @var array Lookup hash of all route objects
     */
    protected $routes;

    /**
     * @var array Lookup hash of named route objects, keyed by route name (lazy-loaded)
     */
    protected $namedRoutes;

    /**
     * @var array Array of route objects that match the request URI (lazy-loaded)
     */
    protected $matchedRoutes;

    /**
     * @var array Array containing all route groups
     */
    protected $routeGroups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = array();
        $this->routeGroups = array();
    }

    /**
     * Get Current Route object or the first matched one if matching has been performed
     * @return \Slim\Route|null
     */
    public function getCurrentRoute()
    {
        if ($this->currentRoute !== null) {
            return $this->currentRoute;
        }

        if (is_array($this->matchedRoutes) && count($this->matchedRoutes) > 0) {
            return $this->matchedRoutes[0];
        }

        return null;
    }

      public function getMatchedRoutes($httpMethod, $resourceUri, $reload = false)
    {
        if ($reload || is_null($this->matchedRoutes)) {
            $this->matchedRoutes = array();
            foreach ($this->routes as $route) {
                if (!$route->supportsHttpMethod($httpMethod) && !$route->supportsHttpMethod("ANY")) {
                    continue;
                }

                if ($route->matches($resourceUri)) {
                    $this->matchedRoutes[] = $route;
                }
            }
        }

        return $this->matchedRoutes;
    }

    /**
     * Add a route object to the router
     * @param  \Slim\Route     $route      The Slim Route
     */
    public function map(\Slim\Route $route)
    {
        list($groupPattern, $groupMiddleware) = $this->processGroups();

        $route->setPattern($groupPattern . $route->getPattern());
        $this->routes[] = $route;


        foreach ($groupMiddleware as $middleware) {
            $route->setMiddleware($middleware);
        }
    }

    /**
     * A helper function for processing the group's pattern and middleware
     * @return array Returns an array with the elements: pattern, middlewareArr
     */
    protected function processGroups()
    {
        $pattern = "";
        $middleware = array();
        foreach ($this->routeGroups as $group) {
            $k = key($group);
            $pattern .= $k;
            if (is_array($group[$k])) {
                $middleware = array_merge($middleware, $group[$k]);
            }
        }
        return array($pattern, $middleware);
    }

 
    public function pushGroup($group, $middleware = array())
    {
        return array_push($this->routeGroups, array($group => $middleware));
    }

    /**
     * Removes the last route group from the array
     * @return bool    True if successful, else False
     */
    public function popGroup()
    {
        return (array_pop($this->routeGroups) !== null);
    }


    public function urlFor($name, $params = array())
    {
        if (!$this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route not found for name: ' . $name);
        }
        $search = array();
        foreach ($params as $key => $value) {
            $search[] = '#:' . preg_quote($key, '#') . '\+?(?!\w)#';
        }
        $pattern = preg_replace($search, $params, $this->getNamedRoute($name)->getPattern());

        //Remove remnants of unpopulated, trailing optional pattern segments, escaped special characters
        return preg_replace('#\(/?:.+\)|\(|\)|\\\\#', '', $pattern);
    }

    /**
     * Add named route
     * @param  string            $name   The route name
     * @param  \Slim\Route       $route  The route object
     * @throws \RuntimeException         If a named route already exists with the same name
     */
    public function addNamedRoute($name, \Slim\Route $route)
    {
        if ($this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route already exists with name: ' . $name);
        }
        $this->namedRoutes[(string) $name] = $route;
    }

    /**
     * Has named route
     * @param  string   $name   The route name
     * @return bool
     */
    public function hasNamedRoute($name)
    {
        $this->getNamedRoutes();

        return isset($this->namedRoutes[(string) $name]);
    }


    public function getNamedRoute($name)
    {
        $this->getNamedRoutes();
        if ($this->hasNamedRoute($name)) {
            return $this->namedRoutes[(string) $name];
        } else {
            return null;
        }
    }

    /**
     * Get named routes
     * @return \ArrayIterator
     */
    public function getNamedRoutes()
    {
        if (is_null($this->namedRoutes)) {
            $this->namedRoutes = array();
            foreach ($this->routes as $route) {
                if ($route->getName() !== null) {
                    $this->addNamedRoute($route->getName(), $route);
                }
            }
        }

        return new \ArrayIterator($this->namedRoutes);
    }
}
