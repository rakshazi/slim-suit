<?php
namespace Rakshazi\SlimSuit;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    /**
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(\Pimple\Container $container)
    {
        $this->container = $container;
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
}
