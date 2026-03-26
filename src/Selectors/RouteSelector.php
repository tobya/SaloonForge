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
          $this->routes = $this->getRoutes();
          return collect($this->routes)->map(function($route){
              $forgeRoute = new ForgeRoute($route);
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
         // print_r( $excludeMiddleware);
            $route = $forgeRoute->route;
          if (config('saloonforge.routes.exclude.unnamed', false)) {
              if ($route->getName() == null) {
                  return null;
              }
          }

          foreach(config('saloonforge.routes.exclude.filter') as $filter){
              echo "\n filter: $filter  " . $route->uri() . " \n";
              if (Str::is( $filter,$route->uri(),ignoreCase: true)) {
                  return null;
              }
          }


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

              return $forgeRoute;
          }




      }



