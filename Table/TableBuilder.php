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

class TableBuilder implements TableBuilderInterface
{
    protected $columns = array();
    protected $entityAlias='';
    protected $tableFactory;

    public function __construct(
            TableFactoryInterface $tableFactory,
            $entityAlias='t')
    {
        $this->entityAlias = $entityAlias;
        $this->tableFactory = $tableFactory;
    }

    public function add($name, $type=null, $options=array())
    {
        if (!isset($options['sort'])) {
            $options['sort'] = true;
        }
        if ($type) {
            $options['type'] = $type;
        }
        $this->columns[$name] = $options;

        return $this;
    }

    public function getTable()
    {
        return $this->tableFactory->create($this->columns, $this->entityAlias);
    }

}
