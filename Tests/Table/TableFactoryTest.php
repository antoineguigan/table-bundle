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
use Qimnet\TableBundle\Table\TableFactory;

class TableFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $viewFactory;
    protected $factory;
    protected function setUp()
    {
        $this->viewFactory = $this->getMock('Qimnet\TableBundle\Table\TableViewFactoryInterface');
        $this->factory = new TableFactory($this->viewFactory, 'table_default_test_class');
    }
    protected function setupClass($class)
    {
        $this->getMockForAbstractClass('Qimnet\TableBundle\Table\Table', array(
            $this->viewFactory,
            array(),
        ), $class);
    }
    public function testCreate()
    {
        $this->setupClass('table_test_class');
        $this->assertInstanceOf('table_test_class', $this->factory->create(
                array(),
                'table_test_class'));
    }
    public function testCreateWithDefaultClass()
    {
        $this->setupClass('table_default_test_class');
        $this->assertInstanceOf('table_default_test_class', $this->factory->create(
                array()));
    }
}
