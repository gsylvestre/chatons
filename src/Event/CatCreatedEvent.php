<?php

namespace App\Event;

use App\Entity\Cat;
use Symfony\Contracts\EventDispatcher\Event;

class CatCreatedEvent extends Event
{
    protected $cat;

    public function __construct(Cat $cat)
    {
        $this->cat = $cat;
    }

    /**
     * @return Cat
     */
    public function getCat(): Cat
    {
        return $this->cat;
    }
}