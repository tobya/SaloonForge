<?php


    use Tobya\SaloonForge\Services\ConfigService;

    it('can retireve a value from the config file', function () {

        $configService = new ConfigService('Default');
        \Illuminate\Support\Facades\Config::set('saloonforge.integrations.Default.routes.include.middleware',['notWeb']);

        $configValue = $configService->Config('routes.include.middleware');

        expect($configValue)->toBeArray();
        expect($configValue)->toEqual(['notWeb']);

    });


    it('can retireve a value from the config file not default', function () {

        $configService = new ConfigService('Photo');
        \Illuminate\Support\Facades\Config::set('saloonforge.integrations.Photo.routes.include.middleware',['notWeb2']);

        $configValue = $configService->Config('routes.include.middleware');

        expect($configValue)->toBeArray();
        expect($configValue)->toEqual(['notWeb2']);

    });

    it('can see invalid value from the config file', function () {

        $configService = new ConfigService('Photo');
        $configValue = $configService->Config('routes.include.xxxaaabbb');



        expect($configValue)->not()->toBeArray();
        expect($configValue)->toBeNull();

    });
