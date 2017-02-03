<?php
namespace Rakshazi\SlimSuit;

class App extends \Slim\App
{
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
            $this->map($route['methods'], $pattern, function ($request, $response, $args) use ($route) {
                return $this->get('controller_'.lcfirst($route['controller']))->call(
                    $route['action'],
                    $request,
                    $response,
                    $args
                );
            });
        }
    }
}
