<?php

namespace App\PictureResizer;

use claviska\SimpleImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Ceci est un service !
 * Il permet de redimensionner nos images
 */
class PictureResizer
{
    protected $parameterBag;
    protected $em;

    //injection de dépendances !
    //on demande à Symfony de nous passer les services dont nous avons besoin
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
    }

    public function resize(string $filename)
    {
        //crée une version en 1080 de large
        $simpleImage = new SimpleImage();
        $simpleImage
            ->fromFile($this->parameterBag->get('upload_dir') . $filename)
            ->resize(1080)
            //->desaturate()
            ->toFile($this->parameterBag->get('upload_dir').'1080x'.$filename);

        //crée une version en 300px de large

        //crée une version en noir et blanc

        //etc...

        //je pourrais utiliser $this->em pour interagir avec la bdd ici
    }
}