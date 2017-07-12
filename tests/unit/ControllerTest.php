<?php


class ControllerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $app = new \Rakshazi\SlimSuit\App([__DIR__ .'/../_data/config/']);
        $this->controller = new \Rakshazi\SlimSuit\Controller($app);
    }

    protected function _after()
    {
    }

    public function testCall()
    {
        $this->expectException('\Exception');
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $this->controller->call('test'));
        $this->assertArrayHasKey('current_request', $this->controller);
        $this->assertArrayHasKey('current_response', $this->controller);
    }
}
