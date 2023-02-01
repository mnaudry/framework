<?php
namespace App\Listener;
use Bomoyi\Event\ResponseEvent;

class ContentLengthListener {
    public function onResponse(ResponseEvent $event){
        $response = $event->getResponse();
        $headers = $response->headers;
    
        if(!$headers->has("Content-Length")) {
            $headers->set("Content-Length",strlen($response->getContent()));
        }
    }
}