<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/cart' => [[['_route' => 'GetCart', '_controller' => 'App\\Controller\\CartController::GetCart'], null, ['GET' => 0], null, true, false, null]],
        '/api/orders' => [[['_route' => 'getOrders', '_controller' => 'App\\Controller\\CartController::getOrders'], null, ['GET' => 0], null, true, false, null]],
        '/api/checkToken' => [[['_route' => 'checkToken', '_controller' => 'App\\Controller\\CartController::checkToken'], null, ['POST' => 0], null, true, false, null]],
        '/api/product' => [[['_route' => 'listProduct', '_controller' => 'App\\Controller\\CatalogueController::listProduct'], null, ['GET' => 0], null, true, false, null]],
        '/admin/product' => [[['_route' => 'addUniqueProduct', '_controller' => 'App\\Controller\\CatalogueController::addUniqueProduct'], null, ['POST' => 0], null, true, false, null]],
        '/api/register' => [[['_route' => 'register', '_controller' => 'App\\Controller\\UserController::register'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'login', '_controller' => 'App\\Controller\\UserController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/user' => [
            [['_route' => 'userUpdate', '_controller' => 'App\\Controller\\UserController::userUpdate'], null, ['PUT' => 0], null, false, false, null],
            [['_route' => 'userDelete', '_controller' => 'App\\Controller\\UserController::userDelete'], null, ['DELETE' => 0], null, false, false, null],
            [['_route' => 'displayUser', '_controller' => 'App\\Controller\\UserController::displayUser'], null, ['GET' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/a(?'
                    .'|pi/(?'
                        .'|cart/(?'
                            .'|([^/]++)(?'
                                .'|(*:72)'
                            .')'
                            .'|validate(*:88)'
                        .')'
                        .'|orders/([^/]++)(*:111)'
                        .'|product/([^/]++)(?'
                            .'|(*:138)'
                        .')'
                    .')'
                    .'|dmin/product/([^/]++)(*:169)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        72 => [
            [['_route' => 'addUniqueProductToCart', '_controller' => 'App\\Controller\\CartController::addUniqueProductToCart'], ['id'], ['POST' => 0], null, false, true, null],
            [['_route' => 'RemoveUniqueProductToCart', '_controller' => 'App\\Controller\\CartController::RemoveUniqueProductToCart'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        88 => [[['_route' => 'ActivateCart', '_controller' => 'App\\Controller\\CartController::ActivateCart'], [], ['POST' => 0], null, true, false, null]],
        111 => [[['_route' => 'getOrder', '_controller' => 'App\\Controller\\CartController::getOrder'], ['id'], ['GET' => 0], null, false, true, null]],
        138 => [
            [['_route' => 'uniqueProduct', '_controller' => 'App\\Controller\\CatalogueController::uniqueProduct'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'updateUniqueProduct', '_controller' => 'App\\Controller\\CatalogueController::updateUniqueProduct'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        169 => [
            [['_route' => 'removeUniqueProduct', '_controller' => 'App\\Controller\\CatalogueController::removeUniqueProduct'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
