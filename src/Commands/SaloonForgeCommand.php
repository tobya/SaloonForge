<?php

namespace Tobya\SaloonForge\Commands;

use Saloon\Http\Response;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Artisan;
use Tobya\SaloonForge\Generators\RequestGenerator;


class SaloonForgeCommand extends Command
{
    public $signature = 'saloon:forge {integration : The name of the Integration}';

    public $description = 'Forge a Saloon Api from Routes ';

    public function handle(): int
    {


        $RouteSelectorClass = config('saloonforge.routes.selector_class');
        $routeselector = new $RouteSelectorClass();
        // get all routes
        $rz = $routeselector->Routes();
       // print_r($rz);
      //  die();
        $requests = [];
        $integration = $this->argument('integration');
        foreach ($rz as $forgeRoute) {
           // echo "\n $route->uri()";
            //print_r($route->parameterNames());
            $route = $forgeRoute->route;
            $params = collect($route->parameterNames());
            $json_params = json_encode($params);
           // echo $json_params;
            if ($route->getName() != null) {

                $name = str($route->getName())->replace(['.', '-', ' '], ['', '', '']);
            } else {

                $name = str($route->uri())->title()->replace(['.', '-', ' ','/','\\','{','}','?'],
                                ['', '','', '','', '','', '',]) ;
            }
            if ($route->uri() == '/') {
                continue;
            }



            $forgeRequestParameters = ['integration' => $integration,
                'name' => $name->toString(),
                '--method' => $route->methods()[0],
                '--route' => $route->uri(),
                '--params' => $json_params,
                '--namespace' => 'App\Http\Integrations\{integration}\Requests',
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

            $this->info('Creating API Class for ' . $integration);
        $newfire = Blade::render(file_get_contents(__DIR__ . '/../../stubs/saloon.forgefire.blade.php'),
            [
                'integration' => $integration,
                'requests' => $requests]);
        file_put_contents(app_path( '/Http/Integrations/'. $integration   . '/' . $integration . '.php'  ), $newfire);
            return 0;
    }
}
