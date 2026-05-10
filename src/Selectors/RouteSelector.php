<?php

  namespace Tobya\SaloonForge\Selectors;

  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\URL;
  use Illuminate\Routing\RouteCollection;
  use Tobya\SaloonForge\Extensions\ForgeRoute;

  class RouteSelector
  {
      public  $routes = [];

      public function Routes()
      {
          // retrieve all routes
          $this->routes = $this->getRoutes();

          return collect($this->routes)->map(function($route){

              $forgeRouteClass = config('saloonforge.routes.forgeroute_class');
              $forgeRoute = new $forgeRouteClass($route);

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
                  return null;
              }
          }

          // Should we exclude route based on the url containing a filter that
          // shoudl be excluded
          foreach(config('saloonforge.routes.exclude.filter') as $filter){
              echo "\n filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true)) {
                  return null;
              }
          }


          // Exclude via url middleware web | api  etc
          $excludeMiddleware = config('saloonforge.routes.exclude.middleware','');
          if (count($excludeMiddleware) > 0) {
                echo $route->uri() . "\n";
              $middlewares = $route->middleware();
              print_r($middlewares);
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
                  return null;
              }


           }

          // Should we exclude route based on only certain filters should be included
          foreach(config('saloonforge.routes.include.filter') as $filter){
              echo "\n include filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true) === false  ) {

                  return null;
              }
          }



           return $forgeRoute;
      }




  }



