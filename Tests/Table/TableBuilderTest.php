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
use Qimnet\TableBundle\Table\TableBuilder;

class TableBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $tableFactory;
    protected $tableBuilder;

    protected function setUp()
    {
        $this->tableFactory = $this->getMock('Qimnet\TableBundle\Table\TableFactoryInterface');
        $this->tableBuilder = new TableBuilder($this->tableFactory, 'entity_alias');
    }
    public function testAddAndGetTable()
    {
        $this->tableBuilder->add('key1',null,array('option1'=>'value1'));
        $this->tableBuilder->add('key2',null,array('sort'=>false, 'option1'=>'value1'));
        $this->tableBuilder->add('key3','type');
        $this->tableFactory
                ->expects($this->once())
                ->method('create')
                ->with($this->equalTo(array(
                    'key1'=>array('option1'=>'value1','name'=>'key1'),
                    'key2'=>array('sort'=>false,'option1'=>'value1','name'=>'key2'),
                    'key3'=>array('type'=>'type','name'=>'key3')
                )))
                ->will($this->returnValue('success'));
        $this->assertEquals('success', $this->tableBuilder->getTable());
    }

}
