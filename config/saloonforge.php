<?php

// config for Tobya/SaloonForge
return [

    'routes' => [

        'selector_class' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
        'forgeroute_class' => \Tobya\SaloonForge\Extensions\ForgeRoute::class,
        'prefix' => ['/'],
        'exclude' =>
            [
              'middleware' => ['web'],
             'filter' =>        ['admin/*','log-viewer/*'],
             'unnamed' => true,
            ],
        'include' => [
            'middleware' => ['web'],
            'route-parameters' => [
                'any' =>    [],
                'all' => [],  // not implemented
            ],
        ]

        ]


];
