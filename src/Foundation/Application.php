<?php
namespace Bomoyi\Foundation;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;  
use Symfony\Component\HttpKernel\Controller\ControllerResolver;  
use Symfony\Component\HttpKernel\Controller\ArgumentResolver; 
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Application {

    private $contollerResolver;
    private $argumentResolver;

    public function __construct()
    {
       
        $this->contollerResolver = new ControllerResolver();
        $this->argumentResolver = new ArgumentResolver();

    }

    public function setControllerResolver($contollerResolver) {
        $this->contollerResolver = $contollerResolver ;
    }

    public function getControllerResolver()  {
        return $this->contollerResolver;
    }

    public function setArgumentResolver( $argumentResolver) {
        $this->argumentResolver = $argumentResolver ;
    }

    public function getArgumentResolver()  {
        return $this->argumentResolver;
    }

    public function handle(Request $request , RouteCollection $routes ) : Response {
        $context = (new RequestContext())->fromRequest($request);

        $matcher = new UrlMatcher($routes,$context);

        try {
            $request->attributes->add($matcher->match($request->getPathInfo()));
            $controller = $this->contollerResolver->getController($request);

            $arguments = $this->argumentResolver->getArguments($request,$controller);
            return call_user_func_array($controller,$arguments );
        }catch(Exception $e){;
            if($e instanceof ResourceNotFoundException){
                return  new Response('Not found',404);
            } else {
                return  new Response('Server error',500);
            }
        }


    }
}