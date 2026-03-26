<?php

// config for Tobya/SaloonForge
return [

    'routes' => [
        'selector' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
        'prefix' => ['/'],
        'exclude' =>
            [
              'middleware' => ['web'],
             'filter' =>        ['/'],
             'unnamed' => true,
            ],
        'include' => [
            'middleware' => ['web'],
            'route-parameters' => [
                'any' =>    ['{v4}'],
                'all' => [],  // not implemented
            ],
        ]

        ]


];
