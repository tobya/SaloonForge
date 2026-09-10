<?php

use Illuminate\Support\Facades\Config;

it('can get test integration', function () {
    Config::set('saloon.integrations_path', __DIR__);
    $this->artisan('saloon:forgerequest', [
        'integration' => 'Default', 'name' =>'testig', '--route' => '/testing',
        '--params' => '[]', '--namespace' => 'namespace',
    ])

        ->assertExitCode(0)   ;
});
