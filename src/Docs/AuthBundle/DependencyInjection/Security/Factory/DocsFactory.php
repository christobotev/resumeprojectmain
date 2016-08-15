<?php
namespace Docs\AuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class DocsFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.docs.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('auth.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider));

        $listenerId = 'security.authentication.listener.docs.'.$id;
        $container->setDefinition(
            $listenerId,
            new DefinitionDecorator('auth.security.authentication.listener')
        )
        ->addMethodCall('setCheckPaths', $config);

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'form';
    }

    public function getKey()
    {
        return 'auth';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $this->addAuthPathConfig($node);
    }

    private function addAuthPathConfig(NodeDefinition $node)
    {
        $builder = $node->children();
        $builder
            ->scalarNode('setPathToAuthenticate')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
    }
}
