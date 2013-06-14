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
interface TableViewInterface
{
    public function getColumnNames();
    public function getSortColumns();
    public function getMainUrl($entity, array $objectVars=array(), array $parameters=array());
    public function getDeleteUrl($entity, array $objectVars=array(), array $parameters=array());
    public function getColumnLabel($columnName);
    public function getNewAllowed();
    public function getMainActionAllowed($entity, $objectVars);
    public function getEditAllowed($entity, $objectVars);
    public function getDeleteAllowed($entity, $objectVars);
    public function getBatchActionsAllowed($entity, $objectVars);
    public function renderSortLink($columnName);
    public function render($entity, $objectVars, $columnName);
}
