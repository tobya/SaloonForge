<?php echo '<?php' ?>



namespace {{$namespace}};



 use {{$namespace}}\{{$integration}}Connector;
 @foreach ($requests as $request)
  use {{$namespace_withrequest}}\{{$request->name}};
 @endforeach
 use Saloon\Traits\Plugins\AcceptsJson;
 use Saloon\Http\Response;
 use Saloon\Http\Request;

// Client library must composer require tobya/saloon
 // use Tobya\Saloon\SaloonFire;

class {{$integration}}Api extends Tobya\Saloon\SaloonFire
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
        {{-- this is correct indentation --}}
    /**
        * {{$request->name}}
        * @return Response | {{$request->name}}
        */
        public function {{$request->name}}({{$request->parameterlist()}}) : Response | {{$request->name}}
        {

            $request = new {{$request->name}}({{$request->parameterlist()}});

            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);

        }


    @endforeach





}

