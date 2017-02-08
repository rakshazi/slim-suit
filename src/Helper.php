<?php
namespace Rakshazi\SlimSuit;

class Helper
{
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
