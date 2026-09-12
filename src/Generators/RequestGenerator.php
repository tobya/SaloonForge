<?php

  namespace Tobya\SaloonForge\Generators;



  use Illuminate\Routing\Route;

  class RequestGenerator
  {

      public function __construct(    public string $name, public Route | null  $route, public $params  )
      {

      }

      public function parameterlist()
      {
        return collect($this->params)
            ->map(function($v){
                return '$'. $v;
            })
            ->join(',');

      }


  }
