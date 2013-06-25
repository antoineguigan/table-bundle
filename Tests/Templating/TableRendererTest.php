<?php
/*
 * This file is part of the Qimnet CRUD Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\TableBundle\Tests\Templating;

use Qimnet\TableBundle\Templating\TableRenderer;

class TableRendererTest extends \PHPUnit_Framework_TestCase
{
    protected $renderer;
    protected function setUp()
    {
        $this->renderer = new TableRenderer('header_strategy');
    }
    public function getMockStrategy($options, $renderOptions)
    {
        $options = $options + array(
            'priority'=>0,
            'can_render'=>true,
            'success'=>true
        );
        $strategy = $this->getMock('Qimnet\TableBundle\Templating\TableRendererStrategyInterface');
        $strategy->expects($this->any())
                ->method('getPriority')
                ->will($this->returnValue($options['priority']));
        $strategy->expects($this->any())
                ->method('getName')
                ->will($this->returnValue($options['type']));
        $strategy->expects($this->any())
                ->method('canRender')
                ->will($this->returnValue($options['can_render']));
        if ($options['success']) {
            $strategy->expects($this->once())
                    ->method('render')
                    ->with($this->equalTo('value'), $this->equalTo($renderOptions))
                    ->will($this->returnValue('success'));
        } else {
            $strategy->expects($this->never())
                    ->method('render');
        }

        return $strategy;
    }
    public function getRenderData()
    {
        return array(
            array(
                array('type'=>'type1'),
                array(
                    array('type'=>'type1'),
                    array('type'=>'type2','success'=>false)
                )
            ),
            array(
                array(),
                array(
                    array('type'=>'type1','priority'=>100,'can_render'=>false, 'success'=>false),
                    array('type'=>'type2','priority'=>false,'success'=>false),
                    array('type'=>'type3','priority'=>10,'success'=>false),
                    array('type'=>'type4','priority'=>70),
                    array('type'=>'type5','priority'=>50,'success'=>false),
                )
            )
        );
    }

    /**
     * @dataProvider getRenderData
     */
    public function testRender($options, $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->renderer->addStrategy($this->getMockStrategy($strategy, $options));
        }
        $result = $this->renderer->render('value', $options);
        $this->assertEquals('success', $result);
    }
    /**
     * @expectedException RuntimeException
     */
    public function testRenderException()
    {
        $this->renderer->render('value',array());
    }

    public function getRenderSortLinkData()
    {
        return array(
            array(false, '<span class="classes">success</span>'),
            array('url', '<a href="url" class="classes">success</a>')
        );
    }

    /**
     * @dataProvider getRenderSortLinkData
     */
    public function testRenderSortLink($sortUrl, $result)
    {
        $this->renderer->addStrategy($this->getMockStrategy(array('type'=>'header_strategy'), array('type'=>'header_strategy')));
        $this->assertEquals($result, $this->renderer->renderSortLink('value', 'classes', $sortUrl));
    }
}
