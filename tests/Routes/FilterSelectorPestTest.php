<?php



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
        //  echo "\n $route->uri";
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



    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    // 3 should be missing
    expect($routes->count())->toEqual($allRoutes->count() -3);


});


it ('will exclude filtered routes and ',function($filter,$excludedCount) {


        $uniqueRoute = uniqid();

        Route::get("/$uniqueRoute/Tomato/cucumber/spam/123",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/cucumber/tomato/salad/444/abc",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/salad/soup/{matcher}",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/car/jam/abc",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/carpet/jam/123/3322",[\Tobya\SaloonForge\Tests\TestController::class,'test']);

        Route::get("$uniqueRoute/toma/to",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("$uniqueRoute/saladcream",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("salad/$uniqueRoute/saladcream",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.


     Config::set("saloonforge.integrations.Default.routes.exclude.filter" ,[$filter]);


      $allRoutes = Route::getRoutes();

      $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');
      $routes =  $rs->Routes();



    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    // 3 should be missing
    expect($routes->count())->toEqual($allRoutes->count() - $excludedCount);


})->with([
    'exclude tomato' => ['*tomato*', 2 ],
    'exclude cucumber' => ['*cucumber*', 2 ],
    'exclude salad' => ['*salad*', 4 ],
    'exclude cars' => ['*car*', 2 ],
    'exclude just car' => ['*car/*', 1 ],
    'exclude /jam/' => ['*/jam/*', 2 ],
]);


it ('will include filtered routes and ',function($filter,$includedCount) {


        $uniqueRoute = uniqid();

        Route::get("/$uniqueRoute/Tomato/cucumber/spam/123",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/cucumber/tomato/salad/444/abc",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/salad/soup/{matcher}",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/car/jam/abc",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("/$uniqueRoute/carpet/jam/123/3322",[\Tobya\SaloonForge\Tests\TestController::class,'test']);

        Route::get("$uniqueRoute/toma/to",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("$uniqueRoute/saladcream",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        Route::get("salad/$uniqueRoute/saladcream",[\Tobya\SaloonForge\Tests\TestController::class,'test']);
        // 9 but should nto be returned.


     Config::set("saloonforge.integrations.Default.routes.include.filter" ,[$filter]);


      $allRoutes = Route::getRoutes();

      $rs = new \Tobya\SaloonForge\Selectors\RouteSelector('Default');
      $routes =  $rs->Routes();



    expect( $routes
    )->toBeObject();
    //echo $routes->count();
    expect($routes->count())->toBeGreaterThan(0);
    // 3 should be missing
    expect($routes->count())->toEqual( $includedCount);
    //echo json_encode($allRoutes->getRoutes());


})->with([
    'include tomato' => ['*tomato*', 2 ],
    'include cucumber' => ['*cucumber*', 2 ],
    'include salad' => ['*salad*', 4 ],
    'include cars' => ['*car*', 2 ],
    'include just car' => ['*car/*', 1 ],
    'include /jam/' => ['*/jam/*', 2 ],
]);
