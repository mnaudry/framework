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
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Subscriber\ContentLengthSubscriber;
use App\Subscriber\ContentTypeSubscriber;

class Application implements HttpKernelInterface {

    private $contollerResolver;
    private $argumentResolver;
    private $matcher;
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher , ControllerResolver $contollerResolver, ArgumentResolver $argumentResolver, UrlMatcher $matcher)
    {
       
        $this->contollerResolver = $contollerResolver;
        $this->argumentResolver = $argumentResolver;
        $this->matcher = $matcher;
        $this->dispatcher = $dispatcher;

    }

    public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): Response
    {
       // $context = (new RequestContext())->fromRequest($request);
       $context = $this->matcher->getContext()->fromRequest($request);

       try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
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

        $this->dispatcher->addSubscriber(new ContentTypeSubscriber());

        $this->dispatcher->addSubscriber(new ContentLengthSubscriber());
    }


   /* public function handle(Request $request , RouteCollection $routes ) : Response {
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


    }*/
}