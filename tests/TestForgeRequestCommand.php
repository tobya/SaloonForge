<?php

namespace Tobya\SaloonForge\Tests;

use Tobya\SaloonForge\Commands\ForgeRequestCommand;

class TestForgeRequestCommand extends ForgeRequestCommand
{
    public function replaceIntegration($namespace_string): string
    {
        return parent::replaceIntegration($namespace_string);
    }

    public function replaceRoute($stub, $route): string
    {
        return parent::replaceRoute($stub, $route);
    }

}
