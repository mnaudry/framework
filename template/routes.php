<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

function render_template(Request $request){

    ob_start();
    extract($request->attributes->all(),EXTR_SKIP);
    include __DIR__.sprintf("/pages/%s.php",$_route);

    $response = new Response(ob_get_clean());

    return $response;
}

$routeCollection = $containerBuilder->get('routeCollection');

try {
    $routes = Yaml::parseFile(__DIR__."/../config/routes.yml");
} catch(ParseException $exception) {
   $routes = [];
}

array_walk($routes, function($route) use ($routeCollection) {
    if(isset($route['name']) &&  isset($route['path']) && isset($route['controller'])){

        if(isset($route['params'])) {
            $routeCollection->add($route['name'], new Route($route['path'],
            [
                '_controller' => $route['controller'], 
                ...$route['params']
            ]));
        }else {
            $routeCollection->add($route['name'], new Route($route['path'],
            [
                '_controller' => $route['controller']
            ]));
        }
        
    }
    
});


return $routeCollection;