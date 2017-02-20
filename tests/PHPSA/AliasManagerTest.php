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

        self::assertInstanceOf('\PHPSA\AliasManager', $manager);
        self::assertEquals('\Some\Deep\Namespace', $manager->getNamespace());
    }

    /**
     * @covers \PHPSA\AliasManager::__construct
     */
    public function testConstructorWithoutNamespace()
    {
        $manager = new AliasManager();

        self::assertInstanceOf('\PHPSA\AliasManager', $manager);
        self::assertNull($manager->getNamespace());
    }

    /**
     * @covers \PHPSA\AliasManager::getNamespace
     * @covers \PHPSA\AliasManager::setNamespace
     * @depends testConstructorWithNamespace
     */
    public function testNamespaceGetterSetter()
    {
        $manager = new AliasManager('\Some\Initial\Namespace');

        self::assertNull($manager->setNamespace('\New\GoneWithTheOld'));
        self::assertEquals('\New\GoneWithTheOld', $manager->getNamespace());
    }

    /**
     * @covers \PHPSA\AliasManager::add
     */
    public function testAddNamespace()
    {
        $manager = new AliasManager();

        self::assertNull($manager->add('WebThings'));
    }

    /**
     * @covers \PHPSA\AliasManager::isClassImported
     * @depends testAddNamespace
     */
    public function testIsClassImported()
    {
        $manager = new AliasManager();

        self::assertNull($manager->add('WebThings'));

        self::assertTrue($manager->isClassImported('WebThings'));
        self::assertFalse($manager->isClassImported('AnalogStuff'));
    }
}
