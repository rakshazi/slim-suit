<?php


class TraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $app = new \Rakshazi\SlimSuit\App([__DIR__ .'/../_data/config/']);
        $this->root = new \Rakshazi\SlimSuit\Root($app);
    }

    protected function _after()
    {
    }

    public function testRealGetSet()
    {
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->set('property', 'value'));
        $this->assertEquals($this->root->get('property'), 'value');

        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->set('property'));
        $this->assertEquals($this->root->get('property'), null);

        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->set('property'));
        $this->assertEquals($this->root->get('property', false), false);
    }

    public function testMagicCall()
    {
        $this->expectException('ArgumentCountError');
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->__call('set', ['property', 'value']));
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->__call('set', []));
    }

    public function testMagicGetSet()
    {
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->setProperty('value'));
        $this->assertEquals($this->root->getProperty(), 'value');

        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->setProperty());
        $this->assertEquals($this->root->getProperty(), null);

        $this->assertInstanceOf('\Rakshazi\SlimSuit\Root', $this->root->setProperty());
        $this->assertEquals($this->root->getProperty(false), false);
    }

    public function testMagicGet()
    {
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $this->root->response);
    }

    public function testRealMethodCall()
    {
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $this->root->redirect('/'));
    }

    public function testUndefinedMethod()
    {
        $this->expectException('\Exception');
        $this->root->_someUndefinedMethod();
        $this->root->_someUndefinedMethod(2);
    }

    public function testArrayAccess()
    {
        $this->root['property'] = 'value';
        $this->assertArrayHasKey('property', $this->root);
        $this->assertEquals($this->root['property'], 'value');
        unset($this->root['property']);
        $this->assertEquals(isset($this->root['property']), false);
        $this->root[] = 'value';
        $this->assertEquals($this->root[0], 'value');
    }

    public function testSerializable()
    {
        $preserialized = 'C:22:"Rakshazi\SlimSuit\Root":64:{a:2:{s:9:"property1";s:6:"value1";s:9:"property2";s:6:"value2";}}';
        $this->root->setProperty1('value1');
        $this->root->setProperty2('value2');

        $serializedRoot = serialize($this->root);
        $this->assertEquals($preserialized, $serializedRoot);
        $this->assertEquals(unserialize($preserialized), unserialize($serializedRoot));
    }
}
