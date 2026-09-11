<?php
use \Tobya\SaloonForge\Tests\TestController;
it('can exclude routes with middleware',function (){

    $uniq = uniqid();
        Route::middleware(['web'])->get("/$uniq/test/123",[TestController::class,'test'])->name('test123');
        Route::middleware(['web'])->get("/$uniq/test/abc/{any}",[TestController::class,'test'])->name('testabc');
        Route::middleware(['web'])->get("/$uniq/test/123/def",[TestController::class,'test'])->name('testdef');
        Route::middleware(['web'])->get("/$uniq/test/acc123",[TestController::class,'test'])->name('test123acc');
        Route::middleware(['api'])->get("/$uniq/test/123bbb",[TestController::class,'test'])->name('test123bbb');
        Route::middleware(['api'])->get("/$uniq/test/123/3322",[TestController::class,'test'])->name('test123223');



     Config::set("saloonforge.integrations.Default.routes.exclude.middleware" ,['api']);


    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();
      $allRoutes = Route::getRoutes();
    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    expect( $allRoutes->count() - $routes->count() )->toEqual(2);
    expect($routes->count())->not()->toEqual(2);

})->with([
    ['web', 4],
    ['api', 2],
]);


it('can exclude routes with middleware and names',function (){



    $uniq = uniqid();
        Route::middleware(['web1'])->get("/$uniq/test44/123",[TestController::class,'test'])->name('test123');
        Route::middleware(['web1'])->get("/$uniq/test44/abc/{any}",[TestController::class,'test'])->name('testabc');
        Route::middleware(['web1'])->get("/$uniq/test44/123/def",[TestController::class,'test'])->name('testdef');
        Route::middleware(['web1'])->get("/$uniq/test44/acc123",[TestController::class,'test'])->name('test123acc');
        Route::middleware(['api1'])->get("/$uniq/test44/123/3322",[TestController::class,'test'])->name('test123223');

        // only one should be returned
        Route::middleware(['api1'])->get("/$uniq/test44/123bbb",[TestController::class,'test']);



     Config::set("saloonforge.integrations.Default.routes.exclude.middleware" ,['web1']);
     Config::set("saloonforge.integrations.Default.routes.exclude.unnamed" ,true);

     // laravel auto creates 2 storage routes, filter them out.
     Config::set("saloonforge.integrations.Default.routes.exclude.filter" ,['*storage*']);



    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();
      $allRoutes = Route::getRoutes();


      foreach($allRoutes as $route){
      //    echo "\n $route->uri";
      }

      foreach($routes as $route){
       //   echo "\n " . $route->route->uri;
      }

     // echo "\n \n " . $allRoutes->count() . " :: " . $routes->count() . "\n";

    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    expect(  $routes->count() )->toEqual(1);


});


it('raises error multiple middleware exclusions',function (){



    $uniq = uniqid();
        Route::middleware(['web12'])->get("/$uniq/test44/123",[TestController::class,'test'])->name('test123');
         Route::middleware(['web12'])->get("/$uniq/test44/acc123",[TestController::class,'test'])->name('test123acc');
        Route::middleware(['api1'])->get("/$uniq/test44/123/3322",[TestController::class,'test'])->name('test123223');

        // only one should be returned
        Route::middleware(['api1'])->get("/$uniq/test44/123bbb",[TestController::class,'test']);



        // this should cause error with multiple until implmeented.
     Config::set("saloonforge.integrations.Default.routes.exclude.middleware" ,['web12','web1']);



    $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');

      $routes =  $rs->Routes();



})->throws(Exception::class);

it('can include routes with middleware',function (){



})->todo('include middleware not implmented');
