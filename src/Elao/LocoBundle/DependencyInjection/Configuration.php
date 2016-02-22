<?php

/*
 * This file is part of the DRYVA project.
 *
 * Copyright (C) 2016 DRYVA
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\LocoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('loco');
        $rootNode
            ->children()

                // Config the locales to download
                ->arrayNode('locales')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()

                // Extra params
                ->scalarNode('key')->isRequired()->end()
                ->scalarNode('target')->defaultValue('%kernel.root_dir%/Resources/translations')->end()
                ->scalarNode('extension')->defaultValue('yml')->end()
                ->scalarNode('format')->defaultValue('nested')->end()
                ->scalarNode('index')->defaultValue('id')->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
