<?php
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


if(!isset($request)) {
    $request = Request::createFromGlobals();
}

$path = $request->getPathInfo();


$response = new Response();

$map = [
    '/hello' => 'hello',
    '/goodbye' => 'goodbye',
];


$go = $map[$path] ?? null ;

if($go){
    ob_start();
    extract($request->query->all(),EXTR_SKIP);
    include __DIR__.sprintf("/template/pages/%s.php",$map[$path]);
    $response->setContent(ob_get_clean());
}else {
    $response->setContent('Not found');
    $response->setStatusCode(404);
}

$response->send();