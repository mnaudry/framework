<?php
namespace Bomoyi\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ResponseEvent extends Event {
    private $response;
    private $request;
    public function __construct(Response $response, Request $request) {
        $this->response = $response ;
        $this->request = $request;
    }

    public function getResponse() {
        return $this->response ;
    }

    public function getRequest() {
        return $this->request;
    }
}
