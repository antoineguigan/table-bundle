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

class TranslatedRendererStrategy implements TableRendererStrategyInterface
{
    protected $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function render($value, array $options=array())
    {
        return htmlspecialchars($this->translator->trans($value));
    }

    public function canRender($value, array $options = array())
    {
        return true;
    }

    public function getPriority()
    {
        return false;
    }

    public function getName()
    {
        return 'translated';
    }
}
