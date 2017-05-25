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
    protected $data;

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
     * @return ReponseInterface
     */
    public function render(string $file, array $variables = []): ResponseInterface
    {
        return $this->view->render($this->response, $file, $variables);
    }

    /**
     * Add flash message, just shortcut to `$this->app->getContainer()->flash->addMessage()`
     * @param string $key
     * @param string $message
     * @param bool $currentRequest Add message for current request, default: no
     */
    public function flash(string $key, string $message, bool $currentRequest = false)
    {
        if ($currentRequest) {
            $this->flash->addMessageNow($key, $message);
        } else {
            $this->flash->addMessage($key, $message);
        }
    }
}
