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

class LinkRendererStrategy extends AbstractTableRendererStrategyDecorator
{
    public function canRender($value, array $options = array())
    {
        return true;
    }
    public function getName()
    {
        return 'link';
    }

    public function getPriority()
    {
        return false;
    }

    public function render($value, array $options = array())
    {
        $template = isset($options['template'])
                ? $options['template']
                : '<a href="%s">%s</a>';
        return sprintf($template, htmlspecialchars($options['link']), $this->renderParent($value, $options));
    }
}
