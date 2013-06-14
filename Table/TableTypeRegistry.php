<?php
/*
 * This file is part of the Qimnet CRUD Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\TableBundle\Table;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TableTypeRegistry implements TableTypeRegistryInterface
{
    protected $container;
    protected $services=array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function add($name, $serviceId)
    {
        $this->services[$name] = $serviceId;
    }

    public function get($name)
    {
        return $this->container->get($this->services[$name]);
    }

    public function has($name)
    {
        return isset($this->services[$name]);
    }
}
