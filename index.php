<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;   
use Symfony\Component\Routing\Exception\RouteNotFoundException; 
use Symfony\Component\HttpKernel\Controller\ControllerResolver;  
use Symfony\Component\HttpKernel\Controller\ArgumentResolver; 


if(!isset($request)) {
    $request = Request::createFromGlobals();
}

$path = $request->getPathInfo();

$context = new RequestContext();
$context = $context->fromRequest($request);

$contollerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();


$routes = include __DIR__."/template/routes.php";

$matcher = new UrlMatcher($routes,$context);


try {
    $request->attributes->add($matcher->match($path));

    $controller = $contollerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request,$controller);
    $response = call_user_func_array($controller,$arguments );

}catch(Exception $e) {
    if($e instanceof RouteNotFoundException){
        $response = new Response('Not found',404);
    } else {
        $response = new Response('Server error',500);
    }
}


$response->send();