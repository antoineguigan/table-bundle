<?php
/*
 * This file is part of the Qimnet CRUD Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\TableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TableRendererCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('qimnet.table.renderer')) {
            return;
        }
        $definition = $container->getDefinition('qimnet.table.renderer');
        $taggedServices = $container->findTaggedServiceIds('qimnet.table.renderer');
        foreach ($taggedServices as $id=>$attributes) {
            $definition->addMethodCall('addStrategy', array(new Reference($id)));
        }
    }
}
