<?php

$routePrefix = strtolower(Configure::read('Admin.routingPrefix'));
$routeName = strtolower(Configure::read('Admin.routingName'));
$pluginName = Configure::read('Admin.pluginName');

Router::connect(
    "/Users/login",
    array(
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'login'
    )
);
Router::connect(
    "/Users/:action/*",
    array(
        'plugin' => 'Users',
        'controller' => 'Users',
        'action' => 'index'
    )
);
Router::promote();
Router::connect(
    "/Users/:action",
    array(
        'plugin' => 'Users',
        'controller' => 'Users',
    )
);
Router::promote();
Router::connect(
    "/{$routeName}/Users/:action/*",
    array(
        $routePrefix => true,
        'plugin' => 'Users',
        'prefix' => $routePrefix,
        'controller' => 'Users',
        'action' => 'index'
    )
);
Router::promote();
Router::connect(
    "/{$routeName}/Users",
    array(
        $routePrefix => true,
        'plugin' => 'Users',
        'prefix' => $routePrefix,
        'controller' => 'Users',
        'action' => 'index'
    )
);
Router::promote();