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
use Qimnet\TableBundle\Routing\PathGeneratorInterface;
use Qimnet\TableBundle\Security\SecurityContextInterface;
use Qimnet\TableBundle\Table\Action;

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

    public function createView(
            PathGeneratorInterface $pathGenerator,
            SecurityContextInterface $securityContext,
            $sortField,
            $sortDirection,
            $mainAction=  Action::UPDATE)
    {
        return $this->viewFactory->create(
                $this->columns,
                $pathGenerator,
                $securityContext,
                $sortField,
                $sortDirection,
                $mainAction);
    }
    public function getColumnSort($columnName)
    {
        $sort = $this->columns[$columnName]['sort'];

        return ($sort===true) ? "$this->entityAlias.$columnName" : $sort;
    }
}
