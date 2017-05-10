<?php
namespace Rakshazi\SlimSuit;

class Helper
{
    /**
     * Adopted for SlimSuit version of rakshazi/get-set-trait package
     * @link https://github.com/rakshazi/getSetTrait
     */
    use Utils\GetSetTrait;

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
}
