<?php

declare(strict_types=1);

/*
 * This file is part of markenzoo/contao-expo-push-notification-bundle.
 *
 * Copyright (c) 2020 markenzoo eG
 *
 * @package   markenzoo/contao-expo-push-notification-bundle
 * @author    Felix Kästner <kaestner@markenzoo.de>
 * @copyright 2020 markenzoo eG
 * @license   https://github.com/markenzoo/contao-expo-push-notification-bundle/blob/master/LICENSE MIT License
 */

namespace Markenzoo\ContaoExpoPushNotificationBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Markenzoo\ContaoExpoPushNotificationBundle\ContaoExpoPushNotificationBundle;
use Solvecrew\ExpoNotificationsBundle\SCExpoNotificationsBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Contao\ManagerPlugin\Config\ContainerBuilder as PluginContainerBuilder;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(SCExpoNotificationsBundle::class), // register Solvecrew\ExpoNotificationsBundle\SCExpoNotificationsBundle manually
            BundleConfig::create(ContaoExpoPushNotificationBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, SCExpoNotificationsBundle::class]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        /*
         * @psalm-suppress PossiblyFalseReference
         */
        return $resolver
             ->resolve(__DIR__.'/../Resources/config/routing.yml')
             ->load(__DIR__.'/../Resources/config/routing.yml')
         ;
    }

    /**
     * Allows a plugin to override extension configuration.
     *
     * @param string           $extensionName
     * @param array            $extensionConfigs
     * @param PluginContainerBuilder $container
     *
     * @return array
     */
    public function getExtensionConfig($extensionName, array $extensionConfigs, PluginContainerBuilder $container)
    {
        if ('doctrine' === $extensionName && Kernel::VERSION < '4.3') 
        {    
            $extensionConfigs[0]['orm']['entity_managers']['default']['mappings']['ContaoExpoPushNotificationBundle'] = [                    
                "type" => "annotation", 
                "dir" => '%kernel.project_dir%/src/Entity'
            ];
        }

        return $extensionConfigs;
    }
}
