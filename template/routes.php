<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();
$routes->add('hello',new Route('/hello/{name}',['name' => 'audry']));
$routes->add('goodbye',new Route('/goodbye}'));


return $routes;