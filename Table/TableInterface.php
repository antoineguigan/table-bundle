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

interface TableInterface
{
    public function createView(
            PathGeneratorInterface $pathGenerator,
            SecurityContextInterface $securityContext,
            $sortField,
            $sortDirection,
            $mainAction=  Action::UPDATE);
    public function getColumnSort($columnName);
}
