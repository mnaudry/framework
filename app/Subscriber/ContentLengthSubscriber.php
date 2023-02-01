<?php
namespace App\Subscriber;
use Bomoyi\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthSubscriber implements  EventSubscriberInterface  {

    public function onResponse(ResponseEvent $event){
        $response = $event->getResponse();
        $headers = $response->headers;
    
        if(!$headers->has("Content-Length")) {
            $headers->set("Content-Length",strlen($response->getContent()));
        }
    }
    public static function getSubscribedEvents(){
        return [
            'responseEvent' => ['onResponse',-255],
        ];
    }
}