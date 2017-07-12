<?php


class RootTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $app = new \Rakshazi\SlimSuit\App([__DIR__ .'/../_data/config/']);
        $this->root = new \Rakshazi\SlimSuit\Root($app);
        session_start();
    }

    protected function _after()
    {
    }

    public function testRender()
    {
        $response = $this->root->render('test.html', ['test' => 'passed']);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertContains('passed', $response->getBody()->__toString());
    }
}
