<?php
namespace Rakshazi\SlimSuit;

class App extends \Slim\App
{
    /**
     * Path to config files
     * Not in container because of realisation nuances
     * @var string
     */
    protected $config_dir;

    public function __construct($container = [])
    {
        parent::__construct();
        if (count($container) == 1 && isset($container[0])) {
            $this->config_dir = $container[0];
            $container = $this->getConfig('core');
        }

        parent::__construct($container);
        $this->initDependencies();
        $this->route($this->getConfig('routes'));
    }

    /**
     * Get config from config files
     * @param string $file Filename (without extension), eg: routes
     * @return array
     */
    public function getConfig(string $file): array
    {
        if (!$this->getContainer()->has('config_'.$file)) {
            $data = require $this->config_dir . $file . '.php';
            $this->getContainer()['config_'.$file] = function ($container) use ($data) {
                return $data;
            };

            return $this->getConfig($file);
        }

        return $this->getContainer()['config_'.$file];
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
                //Tiny hack to provide currend request and response object to all users of container, not only
                //current controller
                $app->getContainer()['current_request'] = function ($c) use ($request) {
                    return $request;
                };
                $app->getContainer()['current_response'] = function ($c) use ($response) {
                    return $response;
                };

                return $app->getController($route['controller'])->call(
                    $route['action'],
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
     * Get instance of helper object by name
     * @param string $name Helper name, eg: Time
     * @return \Rakshazi\SlimSuit\Helper
     */
    public function getHelper(string $name): \Rakshazi\SlimSuit\Helper
    {
        return $this->getByPrefix($this->getContainer()->settings['prefix']['helper'], $name);
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

    /**
     * Add dependencies (such as db and view) into container
     */
    protected function initDependencies()
    {
        $this->getContainer()['db'] = function ($container) {
            return new \Medoo\Medoo($container->get('settings')['database']);
        };

        $this->getContainer()['flash'] = function ($container) {
            return new \Slim\Flash\Messages();
        };

        $this->getContainer()['view'] = function ($container) {
            $settings = $container->get('settings')['view'];
            $view = new \Slim\Views\Twig($settings['template_path'], [
                'cache' => $settings['cache_path']
            ]);
            // Instantiate and add Slim specific extension
            $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
            $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
            $view->addExtension(new \Knlv\Slim\Views\TwigMessages(new \Slim\Flash\Messages()));

            return $view;
        };
    }
}
