<?php
namespace Rakshazi\SlimSuit;

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
     * @return ResponseInterface
     */
    public function call(string $action): ResponseInterface
    {
        return call_user_func([$this, $action.'Action']);
    }
}
