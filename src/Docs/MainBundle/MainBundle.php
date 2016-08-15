<?php

namespace Docs\MainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Docs\MainBundle\DependencyInjection\Compile\DataFiltersPass;

class MainBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // compiler pass for the data filters
        $container->addCompilerPass(new DataFiltersPass());
    }
}
