<?php
namespace Rakshazi\SlimSuit;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    /**
     * @var \Rakshazi\SlimSuit\App
     */
    protected $app;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(\Rakshazi\SlimSuit\App $app)
    {
        $this->app = $app;
    }

    /**
     * Call controller action
     * @param string $action
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function call(string $action, ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        return call_user_func([$this, $action.'Action']);
    }

    /**
     * Render view, just shortcut to `$this->app->getContainer()->view->render()`
     * @param string $file View file
     * @param array $variables List of variables passed to view
     * @return ReponseInterface
     */
    public function render(string $file, array $variables): ResponseInterface
    {
        return $this->app->getContainer()->view->render($this->response, $file, $variables);
    }
}
