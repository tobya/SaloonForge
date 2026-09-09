<?php

use Illuminate\Support\Facades\Route;

it('can get  routes', function () {
    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('test');
    $routes = $rs->getRoutes();

    expect($routes)->toBeObject();
    expect($routes->count())->toBeGreaterThan(0);
});


it('can get  routes added', function () {
    \Illuminate\Support\Facades\Config::set("saloonforge.integrations.Default.routes.forgeroute_class" , \Tobya\SaloonForge\Extensions\ForgeRoute::class);
    //echo config("saloonforge.integrations.Default.routes.forgeroute_class");
    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');
    $routes = $rs->Routes();
   // echo $routes->count() . PHP_EOL;

    Route::get('/test123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');


    $routes_added = $rs->Routes();

    foreach ($routes_added as $froute) {
       // echo $route->uri();
       // echo "\n sd f \n";
    }

   // echo $routes->count() . PHP_EOL;
    expect($routes_added)->toBeObject();
    expect($routes_added->count())->toBeGreaterThan($routes->count());
});
