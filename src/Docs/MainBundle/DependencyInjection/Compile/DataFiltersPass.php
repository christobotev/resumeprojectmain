<?php
namespace Docs\MainBundle\DependencyInjection\Compile;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compoile the filter manager
 * @author h.botev
 *
 */
class DataFiltersPass implements CompilerPassInterface
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has("docs.rest.filter_manager")
            || !$container->has("docs.filter_manager")) {
            return;
        }

        $manager = $container->findDefinition("docs.filter_manager");

        $restManager = $container->findDefinition("docs.rest.filter_manager");

        $filters = $container->findTaggedServiceIds("data_filter");

        foreach ($filters as $id => $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['type'])
                    && $tag['type'] == 'rest'
                    ) {
                        $restManager->addMethodCall(
                            "addFilter",
                            [$tag['filterName'], new Reference($id)]
                        );
                } else {
                    $manager->addMethodCall(
                        "addFilter",
                        [$tag['filterName'], new Reference($id)]
                    );
                }
            }
        }
    }
}
