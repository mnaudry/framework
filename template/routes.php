<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

function render_template(Request $request){

    ob_start();
    extract($request->attributes->all(),EXTR_SKIP);
    include __DIR__.sprintf("/pages/%s.php",$_route);

    $response = new Response(ob_get_clean());

    $response->setTtl(120);

    return $response;
}

$routes = new RouteCollection();
$routes->add('hello',new Route('/hello/{name}',['name' => 'audry','_controller' =>  'render_template']
));
$routes->add('goodbye',new Route('/goodbye',['_controller' => 'render_template']));
$routes->add('home',new Route('/',['_controller' => 'render_template' ]));
// non-desirable side effect .. class is always instantiated
$routes->add('number',new Route('/number',['_controller' => 'App\Controller\NumberController::index' ]));


return $routes;