<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Bomoyi\Foundation\Application;
use App\Subscriber\ContentLengthSubscriber;
use App\Subscriber\ContentTypeSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bomoyi\Event\ResponseEvent;
use Symfony\Component\Routing\RequestContext; 
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;  
use Symfony\Component\HttpKernel\Controller\ArgumentResolver; 
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpKernel\EventListener\RouterListener;

if(!isset($request)) {
    $request = Request::createFromGlobals();
}


$routes = include __DIR__."/template/routes.php";

$dispatcher = new EventDispatcher();

$contollerResolver = new ControllerResolver();

$argumentResolver = new ArgumentResolver();

$requestStack = new RequestStack();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher->addSubscriber(new RouterListener($matcher,$requestStack));

$dispatcher->addSubscriber(new ContentTypeSubscriber());

$dispatcher->addSubscriber(new ContentLengthSubscriber());

$app = new Application($dispatcher,$contollerResolver,$requestStack, $argumentResolver);

//$app = new HttpCache($app, new Store(__DIR__.'/cache'));

$response = $app->handle($request);

$event = new ResponseEvent($response ,$request);

$dispatcher->dispatch($event,'responseEvent');

$response->send();