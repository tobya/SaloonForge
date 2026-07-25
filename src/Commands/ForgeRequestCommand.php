<?php

declare(strict_types=1);

namespace Tobya\SaloonForge\Commands;

use Saloon\Enums\Method;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Saloon\Laravel\Console\Commands\MakeRequest;
use Tobya\SaloonForge\SaloonForgeServiceProvider;
use function Laravel\Prompts\select;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ForgeRequestCommand extends MakeRequest
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'saloon:forgerequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forge a new Saloon request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Saloon Request';

    /**
     * The namespace to place the file
     *
     * @var string
     */
    protected $namespace = '\Http\Integrations\{integration}\Requests';

    /**
     * The default stub
     *
     * @var string
     */
    protected $stub = 'saloon.forgerequest.stub';

    /**
     * Get the options for making a request
     *
     * @return array<int, array<mixed>>
     */
    protected function getOptions(): array
    {

        return [
            ['method', 'm', InputOption::VALUE_REQUIRED, 'the method of the request'],
            ['route', 'r', InputOption::VALUE_REQUIRED, 'the route url of the request'],
            ['params', 'p', InputOption::VALUE_REQUIRED, 'the params of the request'],
            ['namespace', null, InputOption::VALUE_REQUIRED, 'the namespace of the request use {integration} as placeholder'],
        ];
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string|\Closure>
     */


    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): MakeRequest|string
    {
      //  echo "bvuild";
      //  dd('build');

        $method = $this->option('method') ?? 'GET';

        if (! is_string($method)) {
            throw new InvalidArgumentException('The method option must be a string.');
        }

        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceMethod($stub, $method);
        $stub = $this->replaceRoute($stub, $this->option('route','/example'));
        $stub = $this->replaceParams($stub, $this->option('params','[]'));
        $namespace = $this->option('namespace', $name);
        $namespace = $this->replaceIntegration($namespace);
        //echo $namespace . "| $name ----- THE NAMESPACE FOR THE REQUEST \n";
       // print_r($namespace);
       // exit('ENDING HERE');
        return $this->replaceNamespace($stub, $namespace)->replaceClass($stub, $namespace);
    }

    protected function replaceIntegration($namespace_string): string
    {
         return str_replace('{integration}', $this->getIntegration(), $namespace_string);
    }

    protected function replaceRoute(string $stub, string $route): string
    {
        $paramList = json_decode($this->option('params','[]'));
        if (count($paramList) > 0) {

            /**
             * Must have ? version of each parameter also to match.
             */
            $paramJoin = collect($paramList)->map(
                function($v){
                    return "'{" . $v . "}','{" . $v . "?}'";
                })->join(',');
            $paramVars = collect($paramList)->map(
                function($v){
                    return '$this->'. $v . ', $this->'. $v;
                })->join(',');
            $code = " return str('$route')
                             ->replace(
                                    [$paramJoin],
                                    [$paramVars]
                              );";

            return str_replace('{{ return_route }}', $code, $stub);
        }


        return str_replace('{{ return_route }}', "return '$route';", $stub);
    }

    protected function replaceParams(string $stub, string $jsonParamList): string
    {
        $list = json_decode($jsonParamList, true);
        $code = '';
        foreach ($list as $param ) {
        $code .= "\n           public string \$" . $param . ", ";

        }
        return str_replace('{{ params }}', $code, $stub);
    }

    protected function getStub()
    {
        // for some reason this is based on the saloon/laravel-saloon path.
        return $this->resolveStubPath( '\\..\\..\\..\\..\\..\\tobya\\saloonforge\\stubs\\' . $this->resolveStubName());
    }
}
