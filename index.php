<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Bomoyi\Foundation\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bomoyi\Event\ResponseEvent;
use App\Subscriber\ContentLengthSubscriber;
use App\Subscriber\ContentTypeSubscriber;

if(!isset($request)) {
    $request = Request::createFromGlobals();
}

$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new ContentTypeSubscriber());
$dispatcher->addSubscriber(new ContentLengthSubscriber());


$routes = include __DIR__."/template/routes.php";

$app = new Application();


$response = $app->handle($request,$routes);


$event = new ResponseEvent($response ,$request);



$dispatcher->dispatch($event,'responseEvent');


$response->send();