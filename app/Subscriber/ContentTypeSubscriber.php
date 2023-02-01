<?php
namespace App\Subscriber;
use Bomoyi\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentTypeSubscriber implements  EventSubscriberInterface  {

    public function onResponse(ResponseEvent $event){
        $response = $event->getResponse();
        $request = $event->getRequest();
        if(($route = $request->attributes->get('_route')) == "home") {
            $response->setContent(json_encode(['where' => $response->getContent()]));
            $response->headers->set('Content-Type',"application/json");
        }
    }
    public static function getSubscribedEvents(){
        return [
            'responseEvent' => 'onResponse',
        ];
    }
}