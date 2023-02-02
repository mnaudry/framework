<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Bomoyi\Foundation\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bomoyi\Event\ResponseEvent;
use Symfony\Component\Routing\RequestContext;  
use Symfony\Component\HttpKernel\Controller\ControllerResolver;  
use Symfony\Component\HttpKernel\Controller\ArgumentResolver; 
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

if(!isset($request)) {
    $request = Request::createFromGlobals();
}


$routes = include __DIR__."/template/routes.php";

$dispatcher = new EventDispatcher();

$contollerResolver = new ControllerResolver();

$argumentResolver = new ArgumentResolver();

$matcher = new UrlMatcher($routes, new RequestContext());

$app = new Application($dispatcher,$contollerResolver, $argumentResolver, $matcher);

$app = new HttpCache($app, new Store(__DIR__.'/cache'));

$response = $app->handle($request);

$event = new ResponseEvent($response ,$request);

$dispatcher->dispatch($event,'responseEvent');

$response->send();