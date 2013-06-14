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

use Qimnet\TableBundle\Table\TableTypeRegistry;

class TableTypeRegistryTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    protected $repository;

    protected function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->repository = new TableTypeRegistry($this->container);
    }

    public function testAdd()
    {
        $this->repository->add('key1', 'service1');
        $this->repository->add('key2', 'service2');
        $this->assertTrue($this->repository->has('key1'));
        $this->assertFalse($this->repository->has('key3'));
        $this->repository->add('key3', 'service3');
        $this->container->expects($this->once())
                ->method('get')
                ->with($this->equalTo('service3'))
                ->will($this->returnValue('success'));
        $this->assertEquals('success', $this->repository->get('key3'));
    }
}
