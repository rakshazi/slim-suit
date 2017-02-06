<?php
namespace Rakshazi\SlimSuit;

class App extends \Slim\App
{
    public function __construct($container = [])
    {
        parent::__construct($container);

        $this->getContainer()['db'] = function ($c) {
            return new \medoo($c->get('settings')['database']);
        };
    }

    /**
     * Simple router
     * $routes example:
     * <code>
     * [
     *      '/some/pattern/{id}' => [
     *          'controller' => 'controller_name',
     *          'methods' => ['GET', 'POST'],
     *          'action' => 'index',
     *      ],
     * ];
     * </code>
     * Default values:
     * controller = 'default'
     * methods = ['GET']
     * action = 'index'
     * @param array $routes
     */
    public function route(array $routes)
    {
        foreach ($routes as $pattern => $route) {
            $route['controller'] = $route['controller'] ?? 'default';
            $route['action'] = $route['action'] ?? 'index';
            $route['methods'] = $route['methods'] ?? ['GET'];
            $app = $this;
            $this->map($route['methods'], $pattern, function ($request, $response, $args) use ($app, $route) {
                return $app->getController($route['controller'])->call(
                    $route['action'],
                    $request,
                    $response,
                    $args
                );
            });
        }
    }

    /**
     * Get instance of entity object by name
     * @param string $name Entity name, eg: User
     * @return \Rakshazi\SlimSuit\Entity
     */
    public function getEntity(string $name): \Rakshazi\SlimSuit\Entity
    {
        return $this->getByPrefix($this->getContainer()->settings['prefix']['entity'], $name, true);
    }

    /**
     * Get instance of controller object by name
     * @param string $name Controller name, eg: Users
     * @return \Rakshazi\SlimSuit\Controller
     */
    public function getController(string $name): \Rakshazi\SlimSuit\Controller
    {
        return $this->getByPrefix($this->getContainer()->settings['prefix']['controller'], $name);
    }

    /**
     * Get instance of the object by prefix, eg: get entity
     * @param string $prefix Class prefix, eg: \Rakshazi\SlimSuit
     * @param string $name Class name, eg: Entity
     * @param bool $factory Get factory or not
     * @return object
     */
    protected function getByPrefix(string $prefix, string $name, $factory = false)
    {
        if ($this->getContainer()->has($prefix.'_'.$name)) {
            return $this->getContainer()->get($prefix.'_'.$name);
        }

        $app = $this;
        $instance = function ($container) use ($app, $prefix, $name) {
            $class = ucfirst($prefix).'\\'.ucfirst($name);
            return new $class($app);
        };

        if ($factory) {
            $instance = $this->getContainer()->factory($instance);
        }

        $this->getContainer()[$prefix.'_'.$name] = $instance;

        return $this->getByPrefix($prefix, $name);
    }
}
