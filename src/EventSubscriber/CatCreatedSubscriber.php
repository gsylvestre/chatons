<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\CatCreatedEvent;

class CatCreatedSubscriber implements EventSubscriberInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onCatCreatedEvent(CatCreatedEvent $event)
    {
        //$this->em;
        //dd($event->getCat());
    }

    public static function getSubscribedEvents()
    {
        return [
            CatCreatedEvent::class => 'onCatCreatedEvent',
        ];
    }
}
