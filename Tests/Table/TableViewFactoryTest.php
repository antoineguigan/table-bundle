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

use Qimnet\TableBundle\Table\TableViewFactory;

class TableViewFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;
    protected $propertyAccessor;
    protected $tableRenderer;

    protected function setUp()
    {
        $this->propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessorInterface');
        $this->tableRenderer = $this->getMock('Qimnet\TableBundle\Templating\TableRendererInterface');
        $this->factory = new TableViewFactory($this->propertyAccessor, $this->tableRenderer, 'table_view_default_test_class');
    }
    protected function setupClass($class)
    {
        $this->getMockForAbstractClass('Qimnet\TableBundle\Table\TableView', array(
            $this->propertyAccessor,
            $this->tableRenderer,
            array(),
        ), $class);
    }
    public function testCreate()
    {
        $this->setupClass('table_view_default_class');
        $this->assertInstanceOf('table_view_default_class', $this->factory->create(array(),array(),'table_view_default_class'));
    }
    public function testCreateWithDefaultClass()
    {
        $this->setupClass('table_view_default_test_class');
        $this->assertInstanceOf('table_view_default_test_class', $this->factory->create(array()));
    }
}
