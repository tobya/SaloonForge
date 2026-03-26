<?php

  namespace Tobya\SaloonForge\Extensions;

  use Illuminate\Routing\Route;

  class ForgeRoute
  {
        public string $group;
        public function __construct( public Route $route  )
        {
        }




  }
