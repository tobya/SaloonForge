<?php

it('can returns parameter list', function () {
    $list = ['param1' => 'unique1', 'param2' => uniqid(), 'param3' => 45];
    $paramlist = (new \Tobya\SaloonForge\Generators\RequestGenerator('aname', null,$list))->parameterlist() ;

    expect($paramlist)->toBeString();
    expect($paramlist)->toContain('unique1');

});


it('can returns parameter list with no keys ', function () {
      $list = [ 'unique2',  uniqid(),  65];
    $paramlist = (new \Tobya\SaloonForge\Generators\RequestGenerator('aname33', null,$list))->parameterlist() ;

    expect($paramlist)->toBeString();
    expect($paramlist)->toContain('unique2');
});

it('can returns parameter list with route ', function () {

    $r = \Illuminate\Support\Facades\Route::get('/tests1',[\Tobya\SaloonForge\Tests\TestController::class, 'test'])->name('test1');


      $list = [ 'unique2',  uniqid(),  65];

    $paramlist = (new \Tobya\SaloonForge\Generators\RequestGenerator('aname33', $r,$list))->parameterlist() ;

    expect($paramlist)->toBeString();
    expect($paramlist)->toContain('unique2');
});

