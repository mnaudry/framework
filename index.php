<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Bomoyi\Foundation\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bomoyi\Event\ResponseEvent;


if(!isset($request)) {
    $request = Request::createFromGlobals();
}

$dispatcher = new EventDispatcher();

$dispatcher->addListener('responseEvent',function(ResponseEvent $event){
    $response = $event->getResponse();
    $request = $event->getRequest();
    if(($route = $request->attributes->get('_route')) == "home") {
        $response->setContent(json_encode(['where' => $response->getContent()]));
        $response->headers->set('Content-Type',"application/json");
    }

   
});

$dispatcher->addListener('responseEvent', function(ResponseEvent $event){
    $response = $event->getResponse();
    $headers = $response->headers;

    if(!$headers->has("Content-Length")) {
        $headers->set("Content-Length",strlen($response->getContent()));
    }
},-255);


$routes = include __DIR__."/template/routes.php";

$app = new Application();


$response = $app->handle($request,$routes);


$event = new ResponseEvent($response ,$request);



$dispatcher->dispatch($event,'responseEvent');


$response->send();