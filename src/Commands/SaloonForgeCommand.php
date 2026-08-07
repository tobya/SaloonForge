<?php

namespace Tobya\SaloonForge\Commands;

use Saloon\Http\Response;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Artisan;
use Tobya\SaloonForge\Generators\RequestGenerator;


class SaloonForgeCommand extends Command
{
    public $signature = 'saloon:forge {integration : The name of the Integration}';

    public $description = 'Forge a Saloon Api from Routes ';

    public function handle(): int
    {

        $integration = $this->argument('integration');
        $config_path = 'saloonforge.integrations.' . $integration ;

        $RouteSelectorClass = config( $config_path . '.routes.selector_class');
        Log::debug('this is a config ',[$config_path, $integration]);
        Log::debug('this is a config ',[ config( $config_path . '.routes.selector_class')]);
        Log::debug('this is a config ',[$RouteSelectorClass]);
        $routeselector = new $RouteSelectorClass($integration);


        // get all routes
        $rz = $routeselector->Routes();
      //  Log::debug('rout select ', [$routeselector]);
      //  ray($rz);asdfasdf


     //   Log::debug('rz',[$rz]);
      //  die();
        $requests = [];

        foreach ($rz as $forgeRoute) {

            $route = $forgeRoute->route;
            $params = collect($route->parameterNames());
            $json_params = json_encode($params);

            if ($route->getName() != null) {
                $name = str($route->getName())->replace(['.', '-', ' '], ['', '', '']);
            } else {
                $name = str($route->uri())->title()
                            ->replace(  ['.', '-', ' ','/','\\','{','}','?'],
                                        ['', '','', '','', '','', '',]) ;
            }

            if ($route->uri() == '/') {
                continue;
            }


            $namespace = Str(config($config_path . '.namespace') )->finish('\\') . '\Requests' ;

            $forgeRequestParameters = ['integration' => $integration,
                'name' => $name->toString(),
                '--method' => $route->methods()[0],
                '--route' => $route->uri(),
                '--params' => $json_params,
                '--namespace' =>  $namespace //'App\Http\Integrations\{integration}\Requests',
            ];
            ray($forgeRequestParameters);
            $this->info('Creating Forge Request for ' .  $route->uri() ) ;
            Artisan::call('saloon:forgerequest', $forgeRequestParameters);

            $requests[] = new RequestGenerator($name, $route,$params );

        }
            $this->info('Creating Connector for ' . $integration);
            Artisan::call('saloon:connector', ['integration' => $integration,
                'name' => $integration . 'Connector',
            ]);

            $this->info('Creating API Class for   ' . $integration);
        $newfire = Blade::render(file_get_contents(__DIR__ . '/../../stubs/saloon.forgefire.blade.php'),
            [
                'integration' => $integration,
                'requests' => $requests]);
        file_put_contents(app_path( '/Http/Integrations/'. $integration   . '/' . $integration . '.php'  ), $newfire);
            return 0;
    }
}
