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
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Qimnet\TableBundle\Templating\TableRendererInterface;

class TableView implements TableViewInterface
{
    protected $sortField;
    protected $sortDirection;
    protected $mainAction;
    protected $columns = array();
    protected $headerRendererOptions;

    protected $types = array();

    public function __construct(
            PropertyAccessorInterface $propertyAccessor,
            TableRendererInterface $tableRenderer,
            array $columns,
            array $headerRendererOptions=array())
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->tableRenderer = $tableRenderer;
        $this->columns = $columns;
        $this->headerRendererOptions = $headerRendererOptions;
    }

    public function getColumnNames()
    {
        return array_keys($this->columns);
    }

    public function render($object, $objectVars, $columnName)
    {
        $options = $this->columns[$columnName];
        $callback = (isset($options['value_callback'])) ? $options['value_callback'] : array($this, 'getEntityValue');
        $value = call_user_func($callback, $object, $objectVars, $columnName);
        $options['column_name'] = $columnName;
        $options['object'] = $object;
        $options['object_vars'] = $objectVars;
        return $this->tableRenderer->render($value, $options);
    }

    public function getColumnLabel($columnName)
    {
        return isset($this->columns[$columnName]['label']) ? $this->columns[$columnName]['label'] : ucfirst(str_replace('_', ' ', $columnName));
    }

    protected function getEntityValue($entity, $objectVars, $columnName)
    {
        return isset($objectVars[$columnName])
            ? $objectVars[$columnName]
            : $this->propertyAccessor->getValue($entity, $columnName);
    }

    public function renderHeader($columnName)
    {
        $options = $this->headerRendererOptions;
        $options['column_name'] = $columnName;
        return $this->tableRenderer->render($this->getColumnLabel($columnName), $options);
    }

}
