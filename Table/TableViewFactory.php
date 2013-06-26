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

class TableViewFactory implements TableViewFactoryInterface
{
    protected $defaultClass;
    protected $propertyAccessor;
    protected $renderer;

    public function __construct(PropertyAccessorInterface $propertyAccessor,
            TableRendererInterface $renderer,
            $defaultClass)
    {
        $this->defaultClass = $defaultClass;
        $this->propertyAccessor = $propertyAccessor;
        $this->renderer = $renderer;
    }
    public function create(array $columns, array $headerRendererOptions=array(), $class='')
    {
        if (!$class) {
            $class = $this->defaultClass;
        }

        return new $class(
                $this->propertyAccessor,
                $this->renderer,
                $columns,
                $headerRendererOptions);
    }
}
