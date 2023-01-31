<?php
namespace Test\Features;

use PHPUnit\Framework\TestCase;
use Bomoyi\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;

class FrameworkTest extends TestCase {
    public function testNotFoundHandling() {
        $bomoyi = $this->getFrameworkForException(new ResourceNotFoundException());

        $reponse = $bomoyi->handle(Request::create("/toto"),new RouteCollection());

        $this->assertEquals(404,$reponse->getStatusCode());
    }

    private function getFrameworkForException(\Exception $e) {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);

        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($e));

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        $framework = new Application();

        $framework->setControllerResolver($controllerResolver );
        $framework->setArgumentResolver($argumentResolver);

        return $framework;
    }
}