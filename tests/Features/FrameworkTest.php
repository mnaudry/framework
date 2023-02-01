<?php
namespace Test\Features;

use PHPUnit\Framework\TestCase;
use Bomoyi\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;

class FrameworkTest extends TestCase {
    public function testNotFoundHandling() {
        $bomoyi = new Application();

        $reponse = $bomoyi->handle(Request::create("/toto"),new RouteCollection());

        $this->assertEquals(404,$reponse->getStatusCode());
    }

    public function testRouteController() {
        $bomoyi = new Application();
        $routes = new RouteCollection();

        $name = "mnaudry";

        $routes->add('say',new Routing\Route('/say/{name}',['_controller' => 'Test\Features\FrameworkTest::getNameController' ]));

    
        $reponse = $bomoyi->handle(Request::create("/say/{$name}"),$routes);

        $this->assertEquals(200,$reponse->getStatusCode());
        $this->assertEquals($name,$reponse->getContent());
    }

    public static function getNameController($name){
        return  new Response($name);
    }
}