<?php
namespace Rakshazi\SlimSuit;

class Root implements \ArrayAccess, \Serializable
{
    /**
     * Adopted for SlimSuit version of rakshazi/get-set-trait package
     * @link https://github.com/rakshazi/getSetTrait
     */
    use Traits\GetSet;
    use Traits\Serializable;
    use Traits\ArrayAccess;

    /**
     * @var \Rakshazi\SlimSuit\App
     */
    protected $app;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(\Rakshazi\SlimSuit\App $app)
    {
        $this->app = $app;
    }

    /**
     * Get object from app container
     * @param string $name
     * @return object|null
     */
    public function __get($name)
    {
        if ($this->app->getContainer()->has($name)) {
            return $this->app->getContainer()[$name];
        }

        return null;
    }

    /**
     * Render view, just shortcut to `$this->app->getContainer()->view->render()`
     * @param string $file View file
     * @param array $variables List of variables passed to view
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function render(string $file, array $variables = []): \Psr\Http\Message\ResponseInterface
    {
        if (!$this->app->getContainer()->has('view')) {
            return $this->response->withStatus(400);
        }

        return $this->view->render($this->response, $file, $variables);
    }

    /**
     * Redirect, just shortuct to `$this->response->withStatus($httpCode)->withHeader('Location', $url)`
     * @param string $url Url to redirect
     * @param int $httpCode HTTP status code, default: 301
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect(string $url, int $httpCode = 301): \Psr\Http\Message\ResponseInterface
    {
        return $this->response->withStatus($httpCode)->withHeader('Location', $url);
    }

    /**
     * Add flash message, just shortcut to `$this->app->getContainer()->flash->addMessage()`
     * @param string $key
     * @param string $message
     * @param bool $currentRequest Add message for current request, default: no
     */
    public function flash(string $key, string $message, bool $currentRequest = false)
    {
        if (!$this->app->getContainer()->has('flash')) {
            return false;
        }

        if ($currentRequest) {
            $this->flash->addMessageNow($key, $message);
        } else {
            $this->flash->addMessage($key, $message);
        }
    }
}
