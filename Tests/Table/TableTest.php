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
use Qimnet\TableBundle\Table\Table;
class TableTest extends \PHPUnit_Framework_TestCase
{
    protected $viewFactory;
    protected function setUp()
    {
        $this->viewFactory = $this->getMock('Qimnet\TableBundle\Table\TableViewFactoryInterface');

    }
    protected function createTable(array $columns=array())
    {
        return new Table($this->viewFactory, $columns, 'entity_alias');
    }
    public function testCreateView()
    {
        $table = $this->createTable(array('key1'=>'value1'));
        $this->viewFactory
                ->expects($this->once())
                ->method('create')
                ->with(
                        $this->equalTo(array('key1'=>'value1'))
                        )
                ->will($this->returnValue('success'));
        $this->assertEquals('success',$table->createView());
    }
}
