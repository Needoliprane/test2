<?php

namespace ContainerDgBsNY1;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_KQ3e_YtService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.kQ3e.Yt' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.kQ3e.Yt'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'dm' => ['services', 'doctrine_mongodb.odm.default_document_manager', 'getDoctrineMongodb_Odm_DefaultDocumentManagerService', true],
        ], [
            'dm' => '?',
        ]);
    }
}