<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\CatCreatedEvent;

/**
 * Cette classe permet d'exécuter du code lorsque un chat est créé (voir le CatCreatedEvent)
 */
class CatCreatedSubscriber implements EventSubscriberInterface
{
    protected $em;

    //on se fait injecter l'entité manager pour interagir avec la bdd
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //cette méthod est appelée lorqu'un CatCreatedEvent est déclenché
    public function onCatCreatedEvent(CatCreatedEvent $event)
    {
        //$this->em;
        //dd($event->getCat());
    }

    public static function getSubscribedEvents()
    {
        return [
            //lorsque le CatCreatedEvent est déclenché, appelle la méthode nommée onCatCreatedEvent
            CatCreatedEvent::class => 'onCatCreatedEvent',
        ];
    }
}
