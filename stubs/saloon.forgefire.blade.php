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
     /**
     * @var null | Request
     */

      private $shouldReturnRequest = false;

      public function __construct(  )
      {
            $this->connector = new {{$integration}}Connector();
      }


      public function getRequest() : static
      {
          $this->shouldReturnRequest = true;
          return $this;
      }

      public function send(Request $request ) : Response
      {
            return $this->connector->send($request);
      }

    @foreach ($requests as $request)
      /**
        * {{$request->name}}
        * @return Response | {{$request->name}}
        */
        public function {{$request->name}}({{$request->parameterlist()}}) : Response | {{$request->name}}
        {

            $request = new {{$request->name}}({{$request->parameterlist()}});

            // dont send request just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


    @endforeach




}

