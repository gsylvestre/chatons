<?php

namespace App\PictureResizer;

use claviska\SimpleImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureResizer
{
    protected $parameterBag;
    protected $em;

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
    }
}