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

class Table implements TableInterface
{
    protected $viewFactory;
    protected $columns;

    public function __construct(
            TableViewFactoryInterface $viewFactory,
            array $columns)
    {
        $this->columns = $columns;
        $this->viewFactory = $viewFactory;
    }

    public function createView(array $headerRendererOptions=array(), $class='')
    {
        return $this->viewFactory->create($this->columns, $headerRendererOptions, $class);
    }
    public function getOptions($columnName) {
        return $this->columns[$columnName];
    }

    public function has($columnName)
    {
        return isset($this->columns[$columnName]);
    }
}
