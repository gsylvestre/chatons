<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Form\CatType;
use claviska\SimpleImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/chatons/ajouter", name="cat_create")
     */
    public function catCreate(Request  $request, EntityManagerInterface $em)
    {
        $cat = new Cat();
        $catForm = $this->createForm(CatType::class, $cat);

        $catForm->handleRequest($request);

        if ($catForm->isSubmitted() && $catForm->isValid()){
            /** @var UploadedFile $brochureFile */
            $picture = $catForm->get('picture')->getData();

            $newFilename = sha1(uniqid()) . "." . $picture->guessExtension();

            $picture->move($this->getParameter('upload_dir'), $newFilename);

            //crée une version en 1080 de large
            $simpleImage = new SimpleImage();
            $simpleImage
                ->fromFile($this->getParameter('upload_dir') . $newFilename)
                ->resize(1080)
                //->desaturate()
                ->toFile($this->getParameter('upload_dir').'1080x'.$newFilename);

            $cat->setFilename($newFilename);
            $cat->setDateCreated(new \DateTime());
            $cat->setIsSold(false);

            $em->persist($cat);
            $em->flush();
        }

        return $this->render('admin/cat_create.html.twig', [
            'catForm' => $catForm->createView()
        ]);
    }
}
