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
        $pathGenerator = $this->getMock('Qimnet\TableBundle\Routing\PathGeneratorInterface');
        $securityContext = $this->getMock('Qimnet\TableBundle\Security\SecurityContextInterface');
        $table = $this->createTable(array('key1'=>'value1'));
        $this->viewFactory
                ->expects($this->once())
                ->method('create')
                ->with(
                        $this->equalTo(array('key1'=>'value1')),
                        $this->identicalTo($pathGenerator),
                        $this->identicalTo($securityContext),
                        $this->equalTo('sort_field'),
                        $this->equalTo('sort_direction'),
                        $this->equalTo('main_action')
                        )
                ->will($this->returnValue('success'));
        $this->assertEquals('success',
                $table->createView($pathGenerator, $securityContext, 'sort_field', 'sort_direction', 'main_action'));
    }
    public function testGetColumnSort()
    {
        $table = $this->createTable(array(
            'column1'=>array('sort'=>true),
            'column2'=>array('sort'=>false),
            'column3'=>array('sort'=>'sort_field')
        ));
        $this->assertEquals('entity_alias.column1', $table->getColumnSort('column1'));
        $this->assertEquals(false, $table->getColumnSort('column2'));
        $this->assertEquals('sort_field', $table->getColumnSort('column3'));
    }
}
