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
use Tobya\SaloonForge\Services\ConfigService;
use Symfony\Component\Console\Input\InputOption;
use Tobya\SaloonForge\Generators\RequestGenerator;
use Illuminate\Filesystem\Filesystem;

class SaloonForgeCommand extends Command
{
    public $signature = 'saloon:forge {integration : The name of the Integration} {--no-copy}';

    public $description = 'Forge a Saloon Api from Routes ';
    protected string $config_path;


    /**
     * @var array|array[]|bool|bool[]|float|float[]|int|int[]|null[]|string|string[]|null
     */
    protected string|array|bool|int|null|float
                $integration;

    protected ConfigService $configService;




    public function handle(): int
    {

        // Setup
        $integration = $this->argument('integration');
        $this->integration = $integration;
        $this->configService = new ConfigService($integration);

        // Check for errors in integration naming and availability
        if (ctype_lower(substr($integration, 0, 1)))
        {
            $this->error('Integration must start with an uppercase letter');
            return self::FAILURE;
        }

        if ( ! $this->configService->IntegrationExists()){
            $this->error('Integration does not exist in config file, please check your spelling or modify config file');
            return self::FAILURE;
        }


        // Create the route Selector.
        $RouteSelectorClass = $this->configService->Config('routes.selector_class');
        $routeselector = new $RouteSelectorClass($integration);


        // get all routes
        $filteredRoutes = $routeselector->Routes();

        $requests = [];

        foreach ($filteredRoutes as $forgeRoute) {

            $route = $forgeRoute->route;

            if ($route->uri() == '/') {
                continue;
            }

            $params = collect($route->parameterNames());
            $json_params = json_encode($params);

            /**
             * Generate Class name from route name or Route URI
             */
            if ($route->getName() != null) {
                $name = str($route->getName())->replace(['.', '-', ' '], ['', '', '']);
            } else {
                $name = str($route->uri())->title()
                            ->replace(  ['.', '-', ' ','/','\\','{','}','?'],
                                        ['', '','', '','', '','', '',]) ;
            }




            $namespace =  $this->configService->get('namespace');
            $namespace_withRequest = Str($namespace )->finish('\\')     . 'Requests' ;

            // Create a Saloon requeset via the Saloon:ForgeRequest Command.
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

            // Store details of request for SaloonForge Fire Creation.
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

        // Create the fireapi.php file .
        file_put_contents( str(config('saloon.integrations_path'))->finish('/')  . $integration . '/' . $integration . 'Api.php'  , $newfire);

        // if required copy to the specified destination
        $this->CopyOnFinish();

        return 0;
    }

    private function CopyOnFinish()
    {
        /*
         * @var bool $shouldCopy;
         */
        $shouldCopy = (bool) ($this->configService->config( 'output.copy.active'))
                        && ! $this->Option('no-copy') ;



       // print_r([$this->option('copy'), $shouldCopy, $this->configService->config( 'output.copy.active')]);
       // var_dump($this->option('no-copy'), $shouldCopy, $this->configService->config( 'output.copy.active'));

        if ($shouldCopy) {
            $destination = $this->configService->config( 'output.copy.destination');
        } else {
            return;
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

        $this->info('Copying files for ' .  $this->integration . " to \n\t" .  $destination . ' ');

        // Using normal get and put (the whole file string at once)
        foreach($files as $file) {

            $pathinfo = pathinfo($file);
            $filename = $pathinfo['basename'];
            $exceptFiles = collect($this->configService->Config('output.copy.except.files'));

            if($exceptFiles->contains($filename)) {
                $this->line("Skipping $filename");
                continue;
            }

            $this->info('Copy ' . $file );
            $destinationStore->put(
                                    $file,
                                    $fileStore->get($file)
                                );

        }

    }

}
