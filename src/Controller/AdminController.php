<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Event\CatCreatedEvent;
use App\Form\CatType;
use App\PictureResizer\PictureResizer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
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
    public function catCreate(
        Request  $request,
        EntityManagerInterface $em,
        PictureResizer $pictureResizer,
        EventDispatcherInterface $dispatcher
    )
    {
        //crée une instance de chaton
        $cat = new Cat();
        //crée le formulaire en lui associant le chaton
        $catForm = $this->createForm(CatType::class, $cat);

        //récupère les éventuelles données du form et les injecte dans mon $cat
        $catForm->handleRequest($request);

        if ($catForm->isSubmitted() && $catForm->isValid()){
            //récupère le fichier uploadé
            /** @var UploadedFile $brochureFile */
            $picture = $catForm->get('picture')->getData();

            //génère un nom unique au hasard pour le fichier (sécurité)
            $newFilename = sha1(uniqid()) . "." . $picture->guessExtension();

            //déplace le fichier uploadé dans le répertoire web
            //getParameter récupère les paramètre depuis le fichier de config services.yaml
            $picture->move($this->getParameter('upload_dir'), $newFilename);

            //appelle mon service ! voir PictureResizer/PictureResizer.php
            $pictureResizer->resize($newFilename);

            //hydrate mon chaton
            $cat->setFilename($newFilename);
            $cat->setDateCreated(new \DateTime());
            $cat->setIsSold(false);

            //sauvegarde en bdd
            $em->persist($cat);
            $em->flush();

            //génère un nouvel événement perso !
            $event = new CatCreatedEvent($cat);
            $dispatcher->dispatch($event);

            //manque une redirection ici, et un message flash
        }

        //affiche le form
        return $this->render('admin/cat_create.html.twig', [
            'catForm' => $catForm->createView()
        ]);
    }
}
