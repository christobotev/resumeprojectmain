<?php
namespace Docs\RestClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Docs\RestClientBundle\DependencyInjection\Compile\ClientConfigurationCompilerPass;

class RestClientBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ClientConfigurationCompilerPass());
    }
}
