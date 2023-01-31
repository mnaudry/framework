<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Bomoyi\Foundation\Application;


if(!isset($request)) {
    $request = Request::createFromGlobals();
}


$routes = include __DIR__."/template/routes.php";

$app = new Application();


$response = $app->handle($request,$routes);

$response->send();