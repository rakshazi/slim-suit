<?php
namespace Rakshazi\SlimSuit;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller extends Root
{
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
        return call_user_func([$this, $action.'Action']);
    }
}
