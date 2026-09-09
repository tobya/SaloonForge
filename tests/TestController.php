<?php

namespace Tobya\SaloonForge\Tests;

use Illuminate\Support\Facades\Request;

class TestController
{

    public function test(Request $request)
    {
        return $request;
    }
}
