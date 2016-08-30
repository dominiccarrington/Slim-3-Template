<?php
namespace App;

use DI\Bridge\Slim\App as DIBridge;
use DI\ContainerBuilder;

class App extends DIBridge
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            "settings.displayErrorDetails" => getenv("APP_DEBUG"),
        ]);

        $builder->addDefinitions(require CONFIG_DIR . '/container.php');
    }

    // @Override {

    /**
     * Add GET route
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     * @param  boolean $csrf
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function get($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['GET'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    /**
     * Add POST route
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function post($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['POST'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    /**
     * Add PUT route
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function put($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['PUT'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    /**
     * Add PATCH route
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function patch($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['PATCH'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    /**
     * Add DELETE route
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function delete($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['DELETE'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    /**
     * Add OPTIONS route
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function options($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['OPTIONS'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    /**
     * Add route for any HTTP method
     *
     * @param  string $pattern  The route URI pattern
     * @param  callable|string  $callable The route callback routine
     *
     * @return \Slim\Interfaces\RouteInterface
     */
    public function any($pattern, $callable, $csrf = true)
    {
        $route = $this->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $pattern, $callable);

        if ($csrf) {
            $route->add($this->getContainer()->get('csrf'));
        }

        return $route;
    }

    // }
}
