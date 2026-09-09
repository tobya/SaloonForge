<?php

  namespace Tobya\SaloonForge\Selectors;

  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\URL;
  use Illuminate\Support\Facades\Log;
  use Illuminate\Routing\RouteCollection;
  use Tobya\SaloonForge\Extensions\ForgeRoute;

  class RouteSelector
  {

      protected $forgeRouteClass = null;
      protected $integration = null;

      public  $routes = [];

      const string EmptyConfigValue = 'THIS IS A MISSING VALUE';

      public function __construct(string $integration)
      {
          $this->integration = $integration;
          $this->forgeRouteClass =   config("saloonforge.integrations.$integration.routes.forgeroute_class");

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

      protected function filterRoute( ForgeRoute $forgeRoute)
      {
          // retireve the actual route
          $route = $forgeRoute->route;

          // should we exclude routes that do not have a name() associated
          if (config('saloonforge.routes.exclude.unnamed', false)) {
              if ($route->getName() == null) {
                  Log::debug('excluding Route no name ' . $route->uri() );
                  return null;
              }
          }

          // Should we exclude route based on the url containing a filter that
          // shoudl be excluded
          foreach($this->config('routes.exclude.filter') as $filter){
              echo "\n filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true)) {
                  Log::debug('excluding Route via filter ' . $route->uri() );
                  return null;
              }
          }


          // Exclude via url middleware web | api  etc
          $excludeMiddleware = $this->config('routes.exclude.middleware','');
          if (count($excludeMiddleware) > 0) {
              //  echo $route->uri() . "\n";
              $middlewares = $route->middleware();
             // print_r($middlewares);
              $matches = collect($middlewares)->contains(function ($m) use ($excludeMiddleware) {

                  if (strtolower($m) === strtolower($excludeMiddleware[0])) {
                 // echo "\n-------------- do not return --------------------\n";
                      return true;
                  }
                  return false;

              });
              //  dd($matches);
              if ($matches ) {
                  echo "\n REturn null";
                  Log::debug('excluding Route no middle matches ' . $route->uri() );
                  return null;
              }


           }

          // Should we exclude route based on only certain filters should be included
          foreach($this->config('routes.include.filter') as $filter){
              echo "\n include filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true) === false  ) {
Log::debug('excluding Route include filter ' . $route->uri() );
                  return null;
              }
          }



           return $forgeRoute;
      }

      protected function config(string $string) : mixed
      {
           $config_path = 'saloonforge.integrations.' . $this->integration ;
          $integration_config_value = config($config_path . '.' . $string,self::EmptyConfigValue);

          if ($integration_config_value == self::EmptyConfigValue) {
              $config_path = 'saloonforge.integrations.Default' ;
              $integration_config_value = config($config_path . '.' . $string);
          }

          return $integration_config_value;
      }


  }



