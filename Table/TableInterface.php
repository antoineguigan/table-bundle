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

interface TableInterface
{
    public function createView(array $headerRendererOptions=array(), $class='');
    public function getOptions($columnName);
    public function has($columnName);
}
