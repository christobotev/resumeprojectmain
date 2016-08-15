<?php

namespace Docs\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Docs\AuthBundle\DependencyInjection\Security\Factory\DocsFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AuthBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new DocsFactory());
    }
}
