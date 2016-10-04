<?php

namespace Tests\PHPSA;

use PHPSA\AliasManager;

class AliasManagerTest extends TestCase
{
    /**
     * @covers \PHPSA\AliasManager::__construct
     */
    public function testConstructorWithNamespace()
    {
        $manager = new AliasManager('\Some\Deep\Namespace');

        $this->assertInstanceOf('\PHPSA\AliasManager', $manager);
        $this->assertEquals('\Some\Deep\Namespace', $manager->getNamespace());
    }

    /**
     * @covers \PHPSA\AliasManager::__construct
     */
    public function testConstructorWithoutNamespace()
    {
        $manager = new AliasManager();

        $this->assertInstanceOf('\PHPSA\AliasManager', $manager);
        $this->assertNull($manager->getNamespace());
    }

    /**
     * @covers \PHPSA\AliasManager::getNamespace
     * @covers \PHPSA\AliasManager::setNamespace
     * @depends testConstructorWithNamespace
     */
    public function testNamespaceGetterSetter()
    {
        $manager = new AliasManager('\Some\Initial\Namespace');

        $this->assertNull($manager->setNamespace('\New\GoneWithTheOld'));
        $this->assertEquals('\New\GoneWithTheOld', $manager->getNamespace());
    }

    /**
     * @covers \PHPSA\AliasManager::add
     */
    public function testAddNamespace()
    {
        $manager = new AliasManager();

        $this->assertNull($manager->add('WebThings'));
    }

    /**
     * @covers \PHPSA\AliasManager::isClassImported
     * @depends testAddNamespace
     */
    public function testIsClassImported()
    {
        $manager = new AliasManager();

        $this->assertNull($manager->add('WebThings'));

        $this->assertTrue($manager->isClassImported('WebThings'));
        $this->assertFalse($manager->isClassImported('AnalogStuff'));
    }
}
