<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bomoyi\Event\ResponseEvent;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

if(!isset($request)) {
    $request = Request::createFromGlobals();
}

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__."/config"));
$loader->load('container.yml');

$routes = include __DIR__."/template/routes.php";

$containerBuilder->register('matcher', UrlMatcher::class)
->setArguments([$routes, new Reference('context')]);

$containerBuilder->register('listener.router', RouterListener::class)
->setArguments([new Reference('matcher'), new Reference('request_stack')]);

$dispatcher = $containerBuilder->get('dispatcher');

$errorHandler = function (FlattenException $exception) {
    $msg = 'Something went wrong! ('.$exception->getMessage().')';

    return new Response($msg, $exception->getStatusCode());
};

$dispatcher = $containerBuilder->get('dispatcher');

$dispatcher->addSubscriber(new ErrorListener($errorHandler));

$response = $containerBuilder->get('application')->handle($request);


$event = new ResponseEvent($response ,$request);

$dispatcher->dispatch($event,'responseEvent');

$response->send();