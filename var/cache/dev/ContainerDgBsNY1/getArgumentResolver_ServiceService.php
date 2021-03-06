<?php

namespace ContainerDgBsNY1;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getArgumentResolver_ServiceService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'argument_resolver.service' shared service.
     *
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/http-kernel/Controller/ArgumentValueResolverInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/http-kernel/Controller/ArgumentResolver/ServiceValueResolver.php';

        return $container->privates['argument_resolver.service'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'App\\Controller\\CartController::ActivateCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController::GetCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController::RemoveUniqueProductToCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController::addUniqueProductToCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController::checkToken' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController::getOrder' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController::getOrders' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController::addUniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController::listProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController::removeUniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController::uniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController::updateUniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController::displayUser' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController::login' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController::register' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController::userDelete' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController::userUpdate' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Kernel::loadRoutes' => ['privates', '.service_locator.KfbR3DY', 'get_ServiceLocator_KfbR3DYService', true],
            'App\\Kernel::registerContainerConfiguration' => ['privates', '.service_locator.KfbR3DY', 'get_ServiceLocator_KfbR3DYService', true],
            'App\\Kernel::terminate' => ['privates', '.service_locator.KfwZsne', 'get_ServiceLocator_KfwZsneService', true],
            'kernel::loadRoutes' => ['privates', '.service_locator.KfbR3DY', 'get_ServiceLocator_KfbR3DYService', true],
            'kernel::registerContainerConfiguration' => ['privates', '.service_locator.KfbR3DY', 'get_ServiceLocator_KfbR3DYService', true],
            'kernel::terminate' => ['privates', '.service_locator.KfwZsne', 'get_ServiceLocator_KfwZsneService', true],
            'App\\Controller\\CartController:ActivateCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController:GetCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController:RemoveUniqueProductToCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController:addUniqueProductToCart' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController:checkToken' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController:getOrder' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CartController:getOrders' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController:addUniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController:listProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController:removeUniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController:uniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\CatalogueController:updateUniqueProduct' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController:displayUser' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController:login' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController:register' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController:userDelete' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'App\\Controller\\UserController:userUpdate' => ['privates', '.service_locator.kQ3e.Yt', 'get_ServiceLocator_KQ3e_YtService', true],
            'kernel:loadRoutes' => ['privates', '.service_locator.KfbR3DY', 'get_ServiceLocator_KfbR3DYService', true],
            'kernel:registerContainerConfiguration' => ['privates', '.service_locator.KfbR3DY', 'get_ServiceLocator_KfbR3DYService', true],
            'kernel:terminate' => ['privates', '.service_locator.KfwZsne', 'get_ServiceLocator_KfwZsneService', true],
        ], [
            'App\\Controller\\CartController::ActivateCart' => '?',
            'App\\Controller\\CartController::GetCart' => '?',
            'App\\Controller\\CartController::RemoveUniqueProductToCart' => '?',
            'App\\Controller\\CartController::addUniqueProductToCart' => '?',
            'App\\Controller\\CartController::checkToken' => '?',
            'App\\Controller\\CartController::getOrder' => '?',
            'App\\Controller\\CartController::getOrders' => '?',
            'App\\Controller\\CatalogueController::addUniqueProduct' => '?',
            'App\\Controller\\CatalogueController::listProduct' => '?',
            'App\\Controller\\CatalogueController::removeUniqueProduct' => '?',
            'App\\Controller\\CatalogueController::uniqueProduct' => '?',
            'App\\Controller\\CatalogueController::updateUniqueProduct' => '?',
            'App\\Controller\\UserController::displayUser' => '?',
            'App\\Controller\\UserController::login' => '?',
            'App\\Controller\\UserController::register' => '?',
            'App\\Controller\\UserController::userDelete' => '?',
            'App\\Controller\\UserController::userUpdate' => '?',
            'App\\Kernel::loadRoutes' => '?',
            'App\\Kernel::registerContainerConfiguration' => '?',
            'App\\Kernel::terminate' => '?',
            'kernel::loadRoutes' => '?',
            'kernel::registerContainerConfiguration' => '?',
            'kernel::terminate' => '?',
            'App\\Controller\\CartController:ActivateCart' => '?',
            'App\\Controller\\CartController:GetCart' => '?',
            'App\\Controller\\CartController:RemoveUniqueProductToCart' => '?',
            'App\\Controller\\CartController:addUniqueProductToCart' => '?',
            'App\\Controller\\CartController:checkToken' => '?',
            'App\\Controller\\CartController:getOrder' => '?',
            'App\\Controller\\CartController:getOrders' => '?',
            'App\\Controller\\CatalogueController:addUniqueProduct' => '?',
            'App\\Controller\\CatalogueController:listProduct' => '?',
            'App\\Controller\\CatalogueController:removeUniqueProduct' => '?',
            'App\\Controller\\CatalogueController:uniqueProduct' => '?',
            'App\\Controller\\CatalogueController:updateUniqueProduct' => '?',
            'App\\Controller\\UserController:displayUser' => '?',
            'App\\Controller\\UserController:login' => '?',
            'App\\Controller\\UserController:register' => '?',
            'App\\Controller\\UserController:userDelete' => '?',
            'App\\Controller\\UserController:userUpdate' => '?',
            'kernel:loadRoutes' => '?',
            'kernel:registerContainerConfiguration' => '?',
            'kernel:terminate' => '?',
        ]));
    }
}
