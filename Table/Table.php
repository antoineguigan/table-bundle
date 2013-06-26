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
    protected $entityAlias='';

    public function __construct(
            TableViewFactoryInterface $viewFactory,
            array $columns,
            $entityAlias='t')
    {
        $this->entityAlias = $entityAlias;
        $this->columns = $columns;
        $this->viewFactory = $viewFactory;
    }

    public function createView(array $headerRendererOptions=array(), $class='')
    {
        return $this->viewFactory->create($this->columns, $headerRendererOptions, $class);
    }
    public function getColumnSort($columnName)
    {
        $sort = $this->columns[$columnName]['sort'];

        return ($sort===true) ? "$this->entityAlias.$columnName" : $sort;
    }
}
