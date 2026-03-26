<?php echo '<?php' ?>



namespace App\Http\Integrations\{{$integration}};


    @foreach ($requests as $request)
 use App\Http\Integrations\{{$integration}}\Requests\{{$request->name}};
        @endforeach
 use Saloon\Traits\Plugins\AcceptsJson;
 use Saloon\Http\Response;

class {{$integration}}
{
      protected {{$integration}}Connector $connector;

      public function __construct(  )
      {
            $this->connector = new {{$integration}}Connector();
      }

    @foreach ($requests as $request)
      /**
        * {{$request->name}}
        * @return Response
        */
        public function {{$request->name}}({{$request->parameterlist()}}) : Response
        {

            $request = new {{$request->name}}({{$request->parameterlist()}});
            return $this->connector->send($request);

        }


    @endforeach
}

