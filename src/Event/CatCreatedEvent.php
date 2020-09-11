<?php

namespace App\Event;

use App\Entity\Cat;
use Symfony\Contracts\EventDispatcher\Event;

class CatCreatedEvent extends Event
{
    protected $cat;

    //quand on crée cet événement, on passe en même temps le chat créé
    public function __construct(Cat $cat)
    {
        $this->cat = $cat;
    }

    /**
     * permet de récupérer le chat créé
     * @return Cat
     */
    public function getCat(): Cat
    {
        return $this->cat;
    }
}