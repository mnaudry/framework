<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;   
use Symfony\Component\Routing\Exception\RouteNotFoundException;    


if(!isset($request)) {
    $request = Request::createFromGlobals();
}

$path = $request->getPathInfo();

$context = new RequestContext();
$context = $context->fromRequest($request);


//$response = new Response();

$routes = include __DIR__."/template/routes.php";

$matcher = new UrlMatcher($routes,$context);


try {

    //extract($matcher->match($path),EXTR_SKIP);
   // ob_start();
   // include __DIR__.sprintf("/template/pages/%s.php",$_route);
   // $response = new Response(ob_get_clean());
   $request->attributes->add($matcher->match($path));

   $response = call_user_func($request->attributes->get('_controller'),$request);

}catch(Exception $e) {
    if($e instanceof RouteNotFoundException){
        $response = new Response('Not found',404);
    } else {

        $response = new Response('Server error',500);
    }
}


$response->send();