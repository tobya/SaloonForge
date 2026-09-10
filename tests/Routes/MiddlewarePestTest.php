<?php

it('can exclude routes with middleware',function (){

        Route::middleware(['web'])->get('/test/123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123');
        Route::middleware(['web'])->get('/test/abc/{any}',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testabc');
        Route::middleware(['web'])->get('/test/123/def',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('testdef');
        Route::middleware(['web'])->get('/test/acc123',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123acc');
        Route::middleware(['api'])->get('/test/123bbb',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123bbb');
        Route::middleware(['api'])->get('/test/123/3322',[\Tobya\SaloonForge\Tests\TestController::class,'test'])->name('test123223');



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

it('can include routes with middleware',function (){



})->todo('include middleware not implmented');
