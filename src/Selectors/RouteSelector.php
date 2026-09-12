<?php

  namespace Tobya\SaloonForge\Selectors;

  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\URL;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Routing\RouteCollection;
  use Tobya\SaloonForge\Extensions\ForgeRoute;
  use Tobya\SaloonForge\Services\ConfigService;

  class RouteSelector
  {

      protected $forgeRouteClass = null;
      protected $integration = null;

      public  $routes = [];

      protected ConfigService $configService;



      public function __construct(string $integration)
      {
          $this->integration = $integration;
          $this->configService = new ConfigService($integration);
          $this->forgeRouteClass =   $this->configService->config("routes.forgeroute_class");

      }

      public function Routes()
      {
          // retrieve all routes
          $this->routes = $this->getRoutes();

          return collect($this->routes)->map(function($route){

              $forgeRoute = new $this->forgeRouteClass($route);

              // Apply filters to the route. Will return null if route
              // should be filtered out.

              return $this->filterRoute($forgeRoute);

          })->filter();
      }

    /**
     * Get the underlying route collection.
     *
     */
      public  function getRoutes()
      {
          return \Illuminate\Support\Facades\Route::getRoutes();
      }

      /**
       * Checks if route matches filters.  Returns route if it does
       * otherewise null.
       * @param ForgeRoute $forgeRoute
       * @return ForgeRoute|null
       * @throws \Exception
       */
      protected function filterRoute( ForgeRoute $forgeRoute) : ForgeRoute | null
      {
          // retireve the actual route
          $route = $forgeRoute->route;

          // should we exclude routes that do not have a name() associated
          if ($this->configService->config('routes.exclude.unnamed')) {
              if ($route->getName() == null) {
                  Log::debug('excluding Route no name ' . $route->uri() );
                  return null;
              }
          }

          // Should we exclude route based on the url containing a filter that
          // shoudl be excluded
          foreach($this->configService->config('routes.exclude.filter') as $filter){
             // echo "\n filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true)) {
                  Log::debug('excluding Route via filter ' . $route->uri() );
                  return null;
              }
          }


          // Exclude via url middleware web | api  etc
          $excludeMiddleware = $this->configService->config('routes.exclude.middleware');
          if (count($excludeMiddleware) > 0) {

              if (count($excludeMiddleware) > 1){
                  throw new \Exception('Middleware can only have a single value currently.');
              }

              //  echo $route->uri() . "\n";
              $middlewares = $route->middleware();
             // print_r($middlewares);
              $matches = collect($middlewares)->contains(function ($m) use ($excludeMiddleware) {

                  if (strtolower($m) === strtolower($excludeMiddleware[0])) {
                 // echo "\n-------------- do not return --------------------\n" . json_encode($excludeMiddleware,JSON_PRETTY_PRINT);
                      return true;
                  }
                  return false;

              });
              //  dd($matches);
              if ($matches ) {
                 // echo "\n REturn null";
                  Log::debug('excluding Route no middle matches ' . $route->uri() );
                  return null;
              }



           }

          // Should we exclude route based on only certain filters should be included
          foreach($this->configService->config('routes.include.filter') as $filter){
            //  echo "\n include filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true) === false  ) {
                    Log::debug('excluding Route include filter ' . $route->uri() );
                  return null;
              }
          }



           return $forgeRoute;
      }




  }



