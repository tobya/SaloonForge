<?php

// config for Tobya/SaloonForge
return [

    'integrations' => [

        /**
         * Integration
         * This is the default integration, you can make more.  This must match (case sensitive)
         * from the commandline the integration you wish to run.
         */

      'Default' => [


        'routes' => [

            /*
             * If you need complex route selection, you can use a custom Selector class.
             */
            'selector_class' => \Tobya\SaloonForge\Selectors\RouteSelector::class,
            'forgeroute_class' => \Tobya\SaloonForge\Extensions\ForgeRoute::class,



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
                'filter' =>        [],
                'route-parameters' => [
                    'any' =>    [],
                    'all' => [],  // not implemented
                ],
            ]

        ],
         'namespace' => 'App\Http\Integrations\{integration}',

          /*
           * Output Settings
           */

          'output' => [


              /*
               * Copy on Finish
               * Saloon forge is run on an api web app that contains the routes that need to be generated.
               * However the wrapper is a seperate project.  So copy the files at the end of
               * generation to another directory.  You may need to edit Connector.
               */
              'copy' => [
                  'active' => false,
                  'destination' => '',
              ]
          ]


      ]
    ],
];
