<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

function render_template($request){

    ob_start();
    extract($request->attributes->all(),EXTR_SKIP);
    include __DIR__.sprintf("/../template/pages/%s.php",$_route);

    return new Response(ob_get_clean());
}

$routes = new RouteCollection();
$routes->add('hello',new Route('/hello/{name}',['name' => 'audry','_controller' =>  'render_template']
));
$routes->add('goodbye',new Route('/goodbye',['_controller' => 'render_template']));
$routes->add('home',new Route('/',['_controller' => 'render_template' ]));


return $routes;