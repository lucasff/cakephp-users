<?php

$routePrefix = strtolower(Configure::read('Admin.routingPrefix'));
$routeName = strtolower(Configure::read('Admin.routingName'));
$pluginName = Configure::read('Admin.pluginName');

Router::connect(
    "/users/login",
    array(
        'plugin' => 'users',
        'controller' => 'users',
        'action' => 'login'
    )
);
Router::connect(
    "/users/:action/*",
    array(
        'plugin' => 'users',
        'controller' => 'users',
        'action' => 'index'
    )
);
Router::promote();
Router::connect(
    "/users/:action",
    array(
        'plugin' => 'users',
        'controller' => 'users',
    )
);
Router::promote();
Router::connect(
    "/{$routeName}/users/:action/*",
    array(
        $routePrefix => true,
        'plugin' => 'users',
        'prefix' => $routePrefix,
        'controller' => 'users',
        'action' => 'index'
    )
);
Router::promote();
Router::connect(
    "/{$routeName}/users",
    array(
        $routePrefix => true,
        'plugin' => 'users',
        'prefix' => $routePrefix,
        'controller' => 'users',
        'action' => 'index'
    )
);
Router::promote();