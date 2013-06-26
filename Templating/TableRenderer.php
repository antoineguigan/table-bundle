<?php
/*
 * This file is part of the Qimnet CRUD Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\TableBundle\Templating;

class TableRenderer implements TableRendererInterface
{
    private $strategies=array();
    private $sorted;

    public function addStrategy(TableRendererStrategyInterface $strategy)
    {
        $this->strategies[$strategy->getName()] = $strategy;
        $this->sorted = null;
    }

    protected function getSortedStrategies()
    {
        if (!isset($this->sorted)) {
            $sort = array();
            foreach ($this->strategies as $strategy) {
                $priority = $strategy->getPriority();
                if ($priority!==false) {
                    if (!isset($sort[$priority])) {
                        $sort[$priority] = array();
                    }
                    $sort[$priority][] = $strategy;
                }
            }
            krsort($sort);
            $this->sorted = array();
            foreach ($sort as $strategies) {
                foreach ($strategies as $strategy) {
                    $this->sorted[] = $strategy;
                }
            }
        }

        return $this->sorted;
    }
    public function render($value, array $options=array())
    {
        if (!isset($options['type'])) {
            foreach ($this->getSortedStrategies() as $strat) {
                if ($strat->canRender($value)) {
                    $strategy = $strat;
                    break;
                }
            }
            if (!isset($strategy)) {
                throw new \RuntimeException("No strategy found");
            }
        } else {
            $strategy = $this->strategies[$options['type']];
        }

        return $strategy->render($value, $options);
    }
}
