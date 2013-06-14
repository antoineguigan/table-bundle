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
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Qimnet\TableBundle\Templating\TableRendererInterface;

class TableView implements TableViewInterface
{
    protected $sortField;
    protected $sortDirection;
    protected $pathGenerator;
    protected $securityContext;
    protected $propertyAccessor;
    protected $mainAction;
    protected $columns = array();
    protected $tableRenderer;

    protected $types = array();

    public function __construct(
            PropertyAccessorInterface $propertyAccessor,
            TableRendererInterface $tableRenderer,
            array $columns,
            PathGeneratorInterface $pathGenerator,
            SecurityContextInterface $securityContext,
            $sortField,
            $sortDirection,
            $mainAction=  Action::UPDATE)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->tableRenderer = $tableRenderer;
        $this->pathGenerator = $pathGenerator;
        $this->securityContext = $securityContext;
        $this->mainAction = $mainAction;
        $this->sortDirection = $sortDirection;
        $this->sortField = $sortField;
        $this->columns = $columns;
        $this->types['auto'] = array($this, 'renderAuto');
        $this->types['string'] = array($this, 'renderString');
        $this->types['date'] = array($this, 'renderDate');
        $this->types['boolean'] = array($this, 'renderBoolean');
        $this->types['translated'] = array($this, 'renderTranslated');
        $this->types['summary'] = array($this, 'renderSummary');
    }

    public function getSortColumns()
    {
        $columns = array();
        foreach ($this->columns as $columnName=>$column) {
            if ($column['sort']) {
                $columns[] = $columnName;
            }
        }

        return $columns;
    }

    public function getColumnNames()
    {
        return array_keys($this->columns);
    }

    public function render($entity, $objectVars, $columnName)
    {
        $options = $this->columns[$columnName];
        $callback = (isset($options['value_callback'])) ? $options['value_callback'] : array($this, 'getEntityValue');
        $value = call_user_func($callback, $entity, $objectVars, $columnName);

        $options['link'] = $this->getMainActionAllowed($entity, $objectVars)
                ? $this->getMainUrl($entity, $objectVars)
                : "";

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

    public function getNewAllowed()
    {
        return $this->securityContext->isActionAllowed(Action::CREATE);
    }
    public function getEditAllowed($entity, $objectVars)
    {
        return $this->securityContext->isActionAllowed(Action::UPDATE, $entity, $objectVars);
    }
    public function getMainActionAllowed($entity, $objectVars)
    {
        return $this->securityContext->isActionAllowed($this->mainAction, $entity, $objectVars);
    }

    public function getDeleteAllowed($entity, $objectVars)
    {
        return $this->securityContext->isActionAllowed(Action::DELETE, $entity, $objectVars);
    }
    public function getBatchActionsAllowed($entity, $objectVars)
    {
        return $this->securityContext->isActionAllowed(Action::DELETE, $entity, $objectVars);
    }
    public function renderSortLink($columnName)
    {
        if (!in_array($columnName, $this->getSortColumns())) {
            $sortDirection = '';
            $sortUrl = false;
        } else {
            $sortDirection = ($this->sortField == $columnName)
                ? (($this->sortDirection=='asc') ? 'desc' : 'asc')
                : 'asc';
            $sortUrl = $this->pathGenerator->generate(Action::INDEX, array(
                        'sortField'=>$columnName,
                        'sortDirection'=>$sortDirection));
        }

        return $this->tableRenderer->renderSortLink($this->getColumnLabel($columnName), $sortDirection, $sortUrl);
    }

    public function getMainUrl($entity, array $objectVars=array(), array $parameters=array())
    {
        return $this->pathGenerator->generate($this->mainAction, $parameters, $entity, $objectVars);
    }
    public function getDeleteUrl($entity, array $objectVars=array(), array $parameters=array())
    {
        return $this->pathGenerator->generate(Action::DELETE, $parameters, $entity, $objectVars);
    }
}
