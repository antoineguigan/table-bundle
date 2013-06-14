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

abstract class AbstractTableRendererStrategyDecorator implements TableRendererStrategyInterface
{
    private $renderer;
    public function __construct(TableRenderer $renderer)
    {
        $this->renderer = $renderer;
    }
    protected function getDecoratedStrategy(array $options = array())
    {
        return isset($options['parent']) ? $options['parent'] : false;
    }

    protected function renderParent($value, array $options = array())
    {
        if ($this->getDecoratedStrategy($options)) {
            $options['type'] = $this->getDecoratedStrategy($options);
        } else {
            unset($options['type']);
        }

        return $this->renderer->render($value, $options);
    }
}
