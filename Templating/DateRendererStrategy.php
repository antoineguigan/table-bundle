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

class DateRendererStrategy implements TableRendererStrategyInterface
{
    protected $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function canRender($value, array $options=array())
    {
        return $value instanceof \DateTime;
    }

    public function getPriority()
    {
       return 70;
    }

    public function render($value, array $options=array())
    {
        $d = new \IntlDateFormatter($this->translator->getLocale(), \IntlDateFormatter::SHORT,\IntlDateFormatter::SHORT);

        return $d->format($value);
    }

    public function getName()
    {
        return 'date';
    }
}
