<?php
namespace Docs\RestClientBundle\DependencyInjection\Compile;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass that adds some default options to the
 * rest client definitions
 *
 * @author h.botev
 *
 */
class ClientConfigurationCompilerPass implements CompilerPassInterface
{

    /**
     * Add default values to the rest clients
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $restClients = $container->findTaggedServiceIds('rest_client');

        foreach ($restClients as $id => $client) {
            $clientDefinition = $container->findDefinition($id);

            $arguments = $clientDefinition->getArguments();

            if (! isset($arguments[0]['serializer'])) {
                $arguments[0]['serializer'] = new Reference("rest_clinet.responseSerializer");
            }

            if (! isset($arguments[0]['responseClass'])) {
                $arguments[0]['responseClass'] = "Docs\RestClientBundle\Client\Result";
            }

            if (! isset($arguments[0]['collectRequests'])) {
                $arguments[0]['collectRequests'] = "%kernel.debug%";
            }

            if (! isset($arguments[0]['dataListener'])) {
                $arguments[0]['dataListener'] = new Reference("rest_client.dataListener");
            }

            if (! isset($arguments[0]['debug'])) {
                $arguments[0]['debug'] = "%kernel.debug%";
            }

            if (! isset($arguments[0]['logger'])) {
                $arguments[0]['logger'] = new Reference("logger");
            }

            $clientDefinition->setArguments($arguments);
        }
    }
}
