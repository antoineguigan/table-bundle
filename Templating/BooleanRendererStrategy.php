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

use Symfony\Component\Translation\TranslatorInterface;

class BooleanRendererStrategy implements TableRendererStrategyInterface
{
    protected $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function render($value, array $options=array())
    {
        return $this->translator->trans($value ? 'Yes' : 'No');
    }

    public function canRender($value, array $options=array())
    {
        return is_bool($value);
    }

    public function getPriority()
    {
        return 70;
    }

    public function getName()
    {
        return 'boolean';
    }
}
