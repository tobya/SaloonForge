<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

// exisating route coutn = 2

beforeEach(function () {


        Route::get('/test/123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');
        Route::get('/test/abc/{any}',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testabc');
        Route::get('/test/123/def',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testdef');
        Route::get('/test/acc123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123acc');
        Route::get('/test/123bbb',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123bbb');
        Route::get('/test/123/3322',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123223');
        // 8 to here

        Route::get('/test/noname',[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.

});

it('can get  routes', function () {
    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('test');
    $routes = $rs->getRoutes();

    expect($routes)->toBeObject();
    expect($routes->count())->toBeGreaterThan(0);
});


it('can get  routes added with integration', function ($integration) {
    \Illuminate\Support\Facades\Config::set("saloonforge.integrations.$integration.routes.forgeroute_class" , \Tobya\SaloonForge\Extensions\ForgeRoute::class);
    //echo config("saloonforge.integrations.Default.routes.forgeroute_class");
    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector($integration);
    $routes = $rs->Routes();
   // echo $routes->count() . PHP_EOL;

    Route::get('/test123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');


    $routes_added = $rs->Routes();

   // echo $routes->count() . PHP_EOL;
    expect($routes_added)->toBeObject();
    expect($routes_added->count())->toBeGreaterThan($routes->count());
})->with([
    'test',
    'Default',
]);

it ('will not return unnamed routes',function() {



        Route::get('/test/123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');
        Route::get('/test/abc/{any}',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testabc');
        Route::get('/test/123/def',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testdef');
        Route::get('/test/acc123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123acc');
        Route::get('/test/123bbb',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123bbb');
        Route::get('/test/123/3322',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123223');
        // 8 to here

        Route::get('/test/noname',[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.


     Config::set("saloonforge.integrations.Default.routes.exclude.unnamed" ,true);


    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();
      $allRoutes = Route::getRoutes();
    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    expect($routes->count())->not()->toEqual($allRoutes->count());
    expect($routes->count())->toEqual($allRoutes->count() -1);

});


it ('will  return unnamed routes',function() {



        Route::get('/test/123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');
        Route::get('/test/abc/{any}',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testabc');
        Route::get('/test/123/def',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testdef');
        Route::get('/test/acc123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123acc');
        Route::get('/test/123bbb',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123bbb');
        Route::get('/test/123/3322',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123223');
        // 8 to here

        Route::get('/test/noname',[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.


     Config::set("saloonforge.integrations.Default.routes.exclude.unnamed" ,false);


    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();
      $allRoutes = Route::getRoutes();
    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    expect($routes->count())->toEqual($allRoutes->count());
    expect($routes->count())->not()->toEqual($allRoutes->count() -1);

});


it ('will exclude filtered routes',function() {


        $uniqueRoute = uniqid();

        Route::get("/$uniqueRoute/123",[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');
        Route::get("/$uniqueRoute/abc/{any}",[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testabc');
        Route::get('/house/123/def',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testdef');
        Route::get('/car/acc123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123acc');
        Route::get('/tart/123bbb',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123bbb');
        Route::get('/jam/123/3322',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123223');
        // 8 to here

        Route::get("/$uniqueRoute/noname",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.


     Config::set("saloonforge.integrations.Default.routes.exclude.filter" ,["*$uniqueRoute*"]);


    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();
      $allRoutes = Route::getRoutes();

      foreach($allRoutes as $route){
          echo "\n $route->uri";
      }

    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    // 3 should be missing
    expect($routes->count())->toEqual($allRoutes->count() -3);
    expect($routes->count())->not()->toEqual(3);

});

it ('will exclude filtered routes at beginning',function() {


        $uniqueRoute = uniqid();

        Route::get("/$uniqueRoute/123",[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');
        Route::get("/$uniqueRoute/abc/{any}",[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testabc');
        Route::get('/house/123/def',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testdef');
        Route::get('/car/acc123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123acc');
        Route::get('/jam/123/3322',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123223');
        // should not be excluuded
        Route::get("/tart/$uniqueRoute",[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123bbb');
        // 8 to here

        Route::get("/$uniqueRoute/noname",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.


     Config::set("saloonforge.integrations.Default.routes.exclude.filter" ,["$uniqueRoute/*"]);


    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();
      $allRoutes = Route::getRoutes();

      foreach($allRoutes as $route){
        //  echo "\n $route->uri";
      }

    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    // 3 should be missing
    expect($routes->count())->toEqual($allRoutes->count() -3);


})->with([
    
]);
