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

class TableFactory implements TableFactoryInterface
{
    protected $viewFactory;
    protected $defaultClass;

    public function __construct(TableViewFactoryInterface $viewFactory, $defaultClass)
    {
        $this->viewFactory = $viewFactory;
        $this->defaultClass = $defaultClass;
    }

    public function create(
            array $columns,
            $class = '')
    {
        if (!$class) {
            $class = $this->defaultClass;
        }

        return new $class(
                $this->viewFactory,
                $columns);
    }
}
