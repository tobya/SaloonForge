<?php

namespace Tobya\SaloonForge\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Artisan;
use Tobya\SaloonForge\Generators\RequestGenerator;


class SaloonForgeCommand extends Command
{
    public $signature = 'saloon:forge {integration}';

    public $description = 'Forge a Saloon Api from Routes ';

    public function handle(): int
    {

        $RouteSelectorClass = config('saloonforge.routes.selector');
        $routeselector = new $RouteSelectorClass();
        // get all routes
        $rz = $routeselector->Routes();
       // print_r($rz);
      //  die();
        $requests = [];
        $integration = $this->argument('integration');
        foreach ($rz as $route) {
            echo "\n $route->uri()";
            //print_r($route->parameterNames());
            $params = collect($route->parameterNames());
            $json_params = json_encode($params);
            echo $json_params;
            if ($route->getName() != null) {

                $name = str($route->getName())->replace(['.', '-', ' '], ['', '', '']);
            } else {

                $name = str($route->uri())->slug();
            }
            if ($route->uri() == '/') {
                continue;
            }

            Artisan::call('saloon:forgerequest', ['integration' => $integration,
                'name' => $name,
                '--method' => $route->methods()[0],
                '--route' => $route->uri(),
                '--params' => $json_params,
            ]);

            $requests[] = new RequestGenerator($name, $route,$params );

        }

            Artisan::call('saloon:connector', ['integration' => $integration,
                'name' => $integration . 'Connector',
            ]);
        $newfire = Blade::render(file_get_contents(__DIR__ . '/../../stubs/saloon.forgefire.blade.php'),
            [
                'integration' => $integration,
                'requests' => $requests]);
        file_put_contents(app_path( '/Http/Integrations/'. $integration   . '/' . $integration . '.php'  ), $newfire);
            return 0;
    }
}
