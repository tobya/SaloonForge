<?php

use Illuminate\Support\Facades\Config;

it('can get test integration', function () {
    Config::set('saloon.integrations_path', base_path('app/tests/Integrations'));
    $this->artisan('saloon:forgerequest', [
        'integration' => 'Default', 'name' =>'testig', '--route' => '/testing',
        '--params' => '[]', '--namespace' => 'namespace',
    ])

        ->assertExitCode(0)   ;
});
