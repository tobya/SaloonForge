<?php echo '<?php' ?>



namespace {{$namespace}};



 use {{$namespace}}\{{$integration}}Connector;
 @foreach ($requests as $request)
  use {{$namespace_withrequest}}\{{$request->name}};
 @endforeach
 use Saloon\Traits\Plugins\AcceptsJson;
 use Saloon\Http\Response;
 use Saloon\Http\Request;

class {{$integration}}Api
{

      protected {{$integration}}Connector $connector;
     /**
     * @var null | Request
     */

      private $shouldReturnRequest = false;

      protected $disableCaching = false;

      public function __construct(  )
      {
            $this->connector = new {{$integration}}Connector();
      }


      public function getRequest($toggle = true) : static
      {
          $this->shouldReturnRequest = $toggle;
          return $this;
      }

      public function send(Request $request ) : Response
      {
            return $this->connector->send($request);
      }


      Protected function getRequest_or_SendForResult($request )
      {
            // apply any modifiers
            $request = $this->applymodifiers($request);

            // if getRequest() has been called, don't actually send request to server,
            // just return the request to caller.
            if ($this->shouldReturnRequest){
                return $request;
            }

            return $this->send($request);
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

            return $this->getRequest_or_SendForResult($request);

        }


    @endforeach




        public function disableCaching($disableCaching = true) : static
        {
            $this->disableCaching = $disableCaching;
            return $this;
        }



      /**
       * Process any modification to Request.
       * @param Request $request
       * @return Response
       */
        protected function applymodifiers(Request $request) : Request
        {
            if ($this->disableCaching){
                if(method_exists($request,'disableCaching'))
                {
                  $request->disableCaching();
                }
            }
            return $request;
        }



}

