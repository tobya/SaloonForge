<?php echo '<?php' ?>



namespace {{$namespace}};



 use {{$namespace}}\{{$integration}}Connector;
 @foreach ($requests as $request)
  use {{$namespace_withrequest}}\{{$request->name}};
 @endforeach

 use Saloon\Traits\Plugins\AcceptsJson;
 use Saloon\Http\Response;
 use Saloon\Http\Request;

// Client library must
//      composer require tobya/saloonfire


class {{$integration}}Api extends \Tobya\SaloonFire\SaloonFire
{

     /**
     * @var {{$integration}}Connector $connector
     */
     protected $connector;



      public function __construct(  )
      {
            $this->connector = new {{$integration}}Connector();
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






}

