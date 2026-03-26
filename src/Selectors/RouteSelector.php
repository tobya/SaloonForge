<?php

  namespace Tobya\SaloonForge\Selectors;

  use Illuminate\Support\Facades\URL;
  use Illuminate\Routing\RouteCollection;

  class RouteSelector
  {
        public  $routes = [];

      public function Routes()
      {
          $this->routes = $this->getRoutes();
          return $this->filterRoutes($this->routes);
      }

    /**
     * Get the underlying route collection.
     *
     */
      public  function getRoutes() 
      {
          return \Illuminate\Support\Facades\Route::getRoutes();
      }

      protected function filterRoutes( $routes)
      {
        //  $routes = collect($routes)->filter(function ($route)  {
        //      $flatroute= url()->query($route->uri())->route();
        //      echo $flatroute . "\n";
        //      return str($flatroute)->startsWith(config('saloonforge.routes.prefix'));
        //  });
          return $routes;
      }

  }
