<?php

// config for Tobya/SaloonForge
return [

    'integrations' => [
      'default' => [


        'routes' => [

            'selector_class' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
            'forgeroute_class' => \Tobya\SaloonForge\Extensions\ForgeRoute::class,

            'prefix' => ['/'],
            'exclude' =>
                [
                    /**
                     * Exclude routes in this middleware
                     */
                  'middleware' => ['web'],
                    /**
                     * Routes matching the below filters should be excluded
                     */
                 'filter' =>        [],

                    /**
                     * Should unnamed routes be excluded
                     */

                 'unnamed' => false,
                ],
            'include' => [
                'middleware' => ['web'],
                'route-parameters' => [
                    'any' =>    [],
                    'all' => [],  // not implemented
                ],
            ]

        ],
         'namespace' => 'App\Http\Integrations\{integration}',
          'output' => [
              'dir' => base_path('Integrations/'),
          ]

      ]
    ],
];
