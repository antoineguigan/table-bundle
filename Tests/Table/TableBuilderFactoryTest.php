<?php
/*
 * This file is part of the Qimnet CRUD Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\TableBundle\Tests\Table;

use Qimnet\TableBundle\Table\TableBuilderFactory;

class TableBuilderFactoryTest  extends \PHPUnit_Framework_TestCase
{
    protected $typeRegistry;
    protected $tableFactory;
    protected $factory;

    protected function setUp()
    {
        $this->typeRegistry=  $this->getMock('Qimnet\TableBundle\Table\TableTypeRegistryInterface');
        $this->tableFactory = $this->getMock('Qimnet\TableBundle\Table\TableFactoryInterface');
        $this->factory = new TableBuilderFactory($this->typeRegistry, $this->tableFactory, 'table_builder_default_test_class');
    }
    protected function setupClass($class)
    {
        $this->getMockForAbstractClass('Qimnet\TableBundle\Table\TableBuilder', array(
            $this->tableFactory,
        ), $class);
    }
    public function testCreate()
    {
        $this->setupClass('table_builder_default_class');
        $this->assertInstanceOf('table_builder_default_class', $this->factory->create(
                'table_builder_default_class'));
    }
    public function testCreateWithDefaultClass()
    {
        $this->setupClass('table_builder_default_test_class');
        $this->assertInstanceOf('table_builder_default_test_class', $this->factory->create());
    }
    protected function getMockTableType($class)
    {
        $tableType = $this->getMock('Qimnet\TableBundle\Table\TableTypeInterface');
        $tableType
                ->expects($this->once())
                ->method('buildTable')
                ->with($this->isInstanceOf($class));

        return $tableType;
    }
    /**
     * @depends testCreate
     */
    public function testCreateFromTypeClass()
    {
        $this->setupClass('table_builder_default_type_test_class');
        $type = $this->getMock('Qimnet\TableBundle\Table\TableTypeInterface', array(), array(), 'table_builder_default_type_test_type_class');
        $this->typeRegistry
                ->expects($this->once())
                ->method('has')
                ->with($this->equalTo('table_builder_default_type_test_type_class'))
                ->will($this->returnValue(false));
        $this->assertInstanceOf('table_builder_default_type_test_class', $this->factory->createFromType('table_builder_default_type_test_type_class', 'table_builder_default_type_test_class'));
    }

    public function testCreateFromTypeService()
    {
        $this->setupClass('table_builder_default_type_test_named_class');
        $type = $this->getMockTableType('table_builder_default_type_test_named_class');
        $this->typeRegistry
                ->expects($this->once())
                ->method('has')
                ->with($this->equalTo('service'))
                ->will($this->returnValue(true));
        $this->typeRegistry
                ->expects($this->once())
                ->method('get')
                ->with($this->equalTo('service'))
                ->will($this->returnValue($type));
        $this->assertInstanceOf('table_builder_default_type_test_named_class', $this->factory->createFromType('service', 'table_builder_default_type_test_named_class'));
    }
    public function testCreateFromTypeObject()
    {
        $this->setupClass('table_builder_default_type_test_object_class');
        $type = $this->getMockTableType('table_builder_default_type_test_object_class');
        $this->assertInstanceOf('table_builder_default_type_test_object_class', $this->factory->createFromType($type, 'table_builder_default_type_test_object_class'));
    }
}
