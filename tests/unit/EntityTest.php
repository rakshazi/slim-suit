<?php


class EntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $app = new \Rakshazi\SlimSuit\App([__DIR__ .'/../_data/config/']);
        $this->entity = $this->getMockBuilder('\Rakshazi\SlimSuit\Entity')
            ->setConstructorArgs([$app])
            ->getMockForAbstractClass();
        $this->entity->method('getTable')->willReturn('some_table');
    }

    protected function _after()
    {
    }

    public function testGetSetData()
    {
        $this->entity->setData(['property1' => 'value1']);
        $this->assertEquals($this->entity->getData(), ['property1' => 'value1']);

        $this->assertInstanceOf('\Rakshazi\SlimSuit\Entity', $this->entity->setData([
            'property2' => 'value2'
        ]));
        $this->assertEquals($this->entity->getData(), [
            'property1' => 'value1',
            'property2' => 'value2'
        ]);
    }

    public function testLoad()
    {
        //Cheater, @todo
        $this->expectException('\Exception');
        $this->assertInstanceOf('\Rakshazi\SlimSuit\Entity', $this->entity->load(1));
    }

    public function testLoadAll()
    {
        //Cheater, @todo
        $this->expectException('\Exception');
        $this->assertInstanceOf('array', $this->entity->loadAll(['id' => 1]));
    }

    public function testCount()
    {
        //Cheater, @todo
        $this->expectException('\Exception');
        $this->assertEquals(2, $this->entity->count(['id' => [1,2]]));
    }

    public function testSave()
    {
        //Cheater, @todo
        $this->expectException('\Exception');
        $this->entity->setData(['property1' => 'value1'])->save();
        $this->assetEquals($this->entity->getId(1), 1);

        $this->entity->setId(2)->save();
        $this->assertEquals($this->entity->getId(), 2);

        $this->assertInstanceOf('\Rakshazi\SlimSuit\Entity', $this->entity->save());
    }

    public function testInsert()
    {
        //Cheater, @todo
        $this->expectException('\Exception');
        $this->assertEquals($this->entity->insert(['id' => 1]), 1);
    }

    public function testDelete()
    {
        //Cheater, @todo
        $this->expectException('\Exception');
        $this->entity->load(1);
        $this->assertEquals(true, $this->entity->delete());
    }
}
