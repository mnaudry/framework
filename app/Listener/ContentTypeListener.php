<?php
namespace App\Listener;
use Bomoyi\Event\ResponseEvent;

class ContentTypeListener {
    public function onResponse(ResponseEvent $event) {
        $response = $event->getResponse();
        $request = $event->getRequest();
        if(($route = $request->attributes->get('_route')) == "home") {
            $response->setContent(json_encode(['where' => $response->getContent()]));
            $response->headers->set('Content-Type',"application/json");
        }
    }
}