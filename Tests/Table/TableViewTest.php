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
use Qimnet\TableBundle\Table\TableView;

class TableViewTest extends \PHPUnit_Framework_TestCase
{
    protected $pathGenerator;
    protected $tableRenderer;

    public function setUp()
    {
        $this->propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessorInterface');
        $this->tableRenderer = $this->getMock('Qimnet\TableBundle\Templating\TableRendererInterface');
    }
    protected function createTableView(array $columns=array())
    {
        return new TableView(
                $this->propertyAccessor,
                $this->tableRenderer,
                $columns);
    }

    public function testGetColumnNames()
    {
        $view = $this->createTableView(array(
            'col1'=>array(),
            'col2'=>array(),
            'col3'=>array(),
        ));
        $this->assertEquals(array('col1', 'col2','col3'), $view->getColumnNames());
    }
    public function getTestRenderData()
    {
        return array(
            array(array(),array(),true),
            array(array('value_callback'=>function(){ return 'value'; }), array(),false),
            array(array(),array('column'=>'value'),false)
        );
    }

    /**
     * @dataProvider getTestRenderData
     */
    public function testRender($options, $objectVars, $callsPropertyAccessor)
    {
        $entity = new \stdClass();
        $view = $this->createTableView(array(
            'column'=>$options,
            'column2'=>array()
        ));


        if ($callsPropertyAccessor) {
            $this->propertyAccessor->expects($this->once())
                    ->method('getValue')
                    ->with($this->identicalTo($entity),  $this->equalTo('column'))
                    ->will($this->returnValue('value'));
        }
        $options['object'] = $entity;
        $options['object_vars'] = $objectVars;
        $options['column_name'] = 'column';
        $this->tableRenderer
                ->expects($this->once())
                ->method('render')
                ->with($this->equalTo('value'), $this->equalTo($options))
                ->will($this->returnValue('success'));

        $this->assertEquals('success', $view->render($entity, $objectVars, 'column'));
    }
    public function testGetColumnLabel()
    {
        $view = $this->createTableView(array(
            'with_label'=>array('label'=>'Label'),
            'without_label'=>array()
        ));
        $this->assertEquals('Label', $view->getColumnLabel('with_label'));
        $this->assertEquals('Without label', $view->getColumnLabel('without_label'));
    }

}
