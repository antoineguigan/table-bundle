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

class TableBuilderFactory  implements TableBuilderFactoryInterface
{
    protected $typeRegistry;
    protected $defaultClass;
    protected $tableFactory;

    public function __construct(
            TableTypeRegistryInterface $typeRegistry,
            TableFactoryInterface $tableFactory,
            $defaultClass) {

        $this->typeRegistry = $typeRegistry;
        $this->defaultClass = $defaultClass;
        $this->tableFactory = $tableFactory;
    }
    public function createFromType($type, $class='')
    {
        $builder = $this->create($class);
        if (is_string($type)) {
            if ($this->typeRegistry->has($type)) {
                $type = $this->typeRegistry->get($type);
            } else {
                $type = new $type;
            }
        }
        $type->buildTable($builder);

        return $builder;
    }

    public function create($class='')
    {
        if (!$class) {
            $class = $this->defaultClass;
        }

        return new $class($this->tableFactory);
    }
}
