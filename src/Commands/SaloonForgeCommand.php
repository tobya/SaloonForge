<?php

namespace Tobya\SaloonForge\Commands;

use IntlChar;
use Saloon\Http\Response;
use Illuminate\Http\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tobya\SaloonForge\Generators\RequestGenerator;


class SaloonForgeCommand extends Command
{
    public $signature = 'saloon:forge {integration : The name of the Integration}';

    public $description = 'Forge a Saloon Api from Routes ';
    protected string $config_path;
    /**
     * @var array|array[]|bool|bool[]|float|float[]|int|int[]|null[]|string|string[]|null
     */
    protected string|array|bool|int|null|float $integration;

    public function handle(): int
    {

        $integration = $this->argument('integration');
        $this->integration = $integration;

        if (ctype_lower(substr($integration, 0, 1)))
        {
            $this->error('Integration must start with an uppercase letter');
            return self::FAILURE;
        }



        $config_path = 'saloonforge.integrations.' . $integration ;
        $this->config_path = $config_path;


        $RouteSelectorClass = config( $config_path . '.routes.selector_class');

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
           echo $route->uri();
          //  echo $route->prefix() . "\n";
            $params = collect($route->parameterNames());
            $json_params = json_encode($params);

            if ($route->getName() != null) {
                $name = str($route->getName())->replace(['.', '-', ' '], ['', '', '']);
                $this->info(' not name:' . $name);
            } else {
                $name = str($route->uri())->title()
                            ->replace(  ['.', '-', ' ','/','\\','{','}','?'],
                                        ['', '','', '','', '','', '',]) ;
                $this->info(' name:' . $name);
            }

            if ($route->uri() == '/') {
                continue;
            }


            $namespace = config($config_path . '.namespace');
            $namespace_withRequest = Str($namespace )->finish('\\')     . 'Requests' ;

            $forgeRequestParameters = ['integration' => STR($integration)->title()->toString(),
                'name' => $name->toString(),
                '--method' => $route->methods()[0],
                '--route' => $route->uri(),
                '--params' => $json_params,
                '--namespace' =>  $namespace_withRequest, //'App\Http\Integrations\{integration}\Requests',
                '--force' => true,
            ];

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
                                            'requests' => $requests,
                                            'namespace' => $namespace,
                                            'namespace_withrequest' => $namespace_withRequest,
                                        ]);
       // echo str(config('saloon.integrations_path'))->finish('/')  . $integration . 'Api.php';

        // Create the fireapi.php file .
        file_put_contents( str(config('saloon.integrations_path'))->finish('/')  . $integration . '/' . $integration . 'Api.php'  , $newfire);

        // if required copy to the sepcifeid destination
        $this->CopyOnFinish();

        return 0;
    }

    private function CopyOnFinish()
    {
        $shouldCopy = config($this->config_path . '.output.copy.active');
        if ($shouldCopy) {
            $destination = config($this->config_path . '.output.copy.destination');
        }

        $fileStore = Storage::build(  [
            'driver' => 'local',
            'root' =>  config('saloon.integrations_path') . $this->integration,
            'throw' => false,
        ]);

        $destinationStore =          Storage::build(  [
            'driver' => 'local',
            'root' => $destination,
            'throw' => false,
        ]);


        // List all the files from a folder
        $files = $fileStore->allFiles('/');

        $this->info('Copying files for ' .  $this->integration . ' to ' .  $destination);

        // Using normal get and put (the whole file string at once)
        foreach($files as $file) {

            $this->info($file );
            $destinationStore->put(
                    $file,
                    $fileStore->get($file)
                );

        }

    }
}
