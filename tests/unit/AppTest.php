<?php


class AppTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->app = new \Rakshazi\SlimSuit\App([__DIR__ .'/../_data/config/']);
    }

    protected function _after()
    {
    }

    public function testGetByPrefix()
    {
        include __DIR__ . '/../_data/src/Entity.php';
        include __DIR__ . '/../_data/src/Controller.php';
        include __DIR__ . '/../_data/src/Helper.php';
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Entity', $this->app->getEntity('entity'));
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Controller', $this->app->getController('controller'));
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Helper', $this->app->getHelper('helper'));
    }
}
