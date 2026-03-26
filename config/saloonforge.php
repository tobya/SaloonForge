<?php

// config for Tobya/SaloonForge
return [

    'routes' => [
        'selector' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
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
