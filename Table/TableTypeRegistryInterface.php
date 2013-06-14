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

interface TableTypeRegistryInterface
{
    public function add($name, $serviceId);
    public function has($name);
    /**
     * @return TableTypeInterface
     */
    public function get($name);
}
