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

class TextRendererStrategy implements TableRendererStrategyInterface
{
    public function canRender($value, array $options=array())
    {
        return true;
    }

    public function getPriority()
    {
        return 0;
    }

    public function render($value, array $options=array())
    {
        return htmlspecialchars($value);
    }

    public function getName()
    {
        return 'text';
    }
}
