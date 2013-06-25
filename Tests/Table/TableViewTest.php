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
use Qimnet\TableBundle\Table\Action;

class TableViewTest extends \PHPUnit_Framework_TestCase
{
    protected $pathGenerator;
    protected $securityContext;
    protected $propertyAccessor;
    protected $tableRenderer;

    public function setUp()
    {
        $this->propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessorInterface');
        $this->tableRenderer = $this->getMock('Qimnet\TableBundle\Templating\TableRendererInterface');
        $this->pathGenerator = $this->getMock('Qimnet\TableBundle\Routing\PathGeneratorInterface');
        $this->securityContext = $this->getMock('Qimnet\TableBundle\Security\SecurityContextInterface');
    }
    protected function createTableView(array $columns=array())
    {
        return new TableView(
                $this->propertyAccessor,
                $this->tableRenderer,
                $columns,
                $this->pathGenerator,
                $this->securityContext,
                'sort_field',
                'asc',
                'main_action');
    }
    public function testGetSortColumns()
    {
        $view = $this->createTableView(array(
            'col1'=>array('sort'=>false),
            'col2'=>array('sort'=>true),
            'col3'=>array('sort'=>true),
            'col4'=>array('sort'=>false),
        ));
        $this->assertEquals(array('col2','col3'), $view->getSortColumns());
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
            array(array(),array(),true,false),
            array(array('value_callback'=>function(){ return 'value'; }), array(),false,true),
            array(array(),array('column'=>'value'),false,false)
        );
    }

    /**
     * @dataProvider getTestRenderData
     */
    public function testRender($options, $objectVars, $callsPropertyAccessor, $editAllowed)
    {
        $entity = new \stdClass();
        $view = $this->createTableView(array(
            'column'=>$options,

            'column2'=>array()
        ));
        $options['link'] = $editAllowed ? 'link' : '';
        $this->securityContext->expects($this->once())
                ->method('isActionAllowed')
                ->with($this->equalTo('main_action'), $this->identicalTo($entity),  $this->equalTo($objectVars))
                ->will($this->returnValue($editAllowed));

        if ($editAllowed) {
            $this->pathGenerator->expects($this->once())
                    ->method('generate')
                    ->with($this->equalTo('main_action'), $this->equalTo(array()), $this->identicalTo($entity), $this->equalTo($objectVars))
                    ->will($this->returnValue('link'));
        }

        if ($callsPropertyAccessor) {
            $this->propertyAccessor->expects($this->once())
                    ->method('getValue')
                    ->with($this->identicalTo($entity),  $this->equalTo('column'))
                    ->will($this->returnValue('value'));
        }

        $this->tableRenderer
                ->expects($this->once())
                ->method('render')
                ->with($this->equalTo('value'), $this->equalTo($options))
                ->will($this->returnValue('success'));

        $this->assertEquals('success', $view->render($entity, $objectVars, 'column'));
    }
    public function testGetNewAllowed()
    {
        $view = $this->createTableView();
        $this->securityContext
                ->expects($this->once())
                ->method('isActionAllowed')
                ->with($this->equalTo(Action::CREATE))
                ->will($this->returnValue('success'));
        $this->assertEquals('success', $view->getNewAllowed());
    }
    public function testGetDeleteAllowed()
    {
        $view = $this->createTableView();
        $this->securityContext
                ->expects($this->once())
                ->method('isActionAllowed')
                ->with($this->equalTo(Action::DELETE), $this->equalTo('entity'), $this->equalTo('object_vars'))
                ->will($this->returnValue('success'));
        $this->assertEquals('success', $view->getDeleteAllowed('entity', 'object_vars'));
    }
    public function testGetBatchActionsAllowed()
    {
        $view = $this->createTableView();
        $this->securityContext
                ->expects($this->once())
                ->method('isActionAllowed')
                ->with($this->equalTo(Action::DELETE), $this->equalTo('entity'), $this->equalTo('object_vars'))
                ->will($this->returnValue('success'));
        $this->assertEquals('success', $view->getBatchActionsAllowed('entity', 'object_vars'));
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

    public function testRenderSortLink()
    {
    }
    public function testGetDeleteUrl()
    {
        $view = $this->createTableView();
        $objectVars = array('key1'=>'value1');
        $this->pathGenerator
                ->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(Action::DELETE),
                        $this->equalTo(array()),
                        $this->equalTo('entity'),
                        $this->equalTo($objectVars))
                ->will($this->returnValue('success'));

        $this->assertEquals('success', $view->getDeleteUrl('entity', $objectVars));
    }
}
