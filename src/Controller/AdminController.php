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
        $cat = new Cat();
        $catForm = $this->createForm(CatType::class, $cat);

        $catForm->handleRequest($request);

        if ($catForm->isSubmitted() && $catForm->isValid()){
            /** @var UploadedFile $brochureFile */
            $picture = $catForm->get('picture')->getData();

            $newFilename = sha1(uniqid()) . "." . $picture->guessExtension();

            $picture->move($this->getParameter('upload_dir'), $newFilename);

            $pictureResizer->resize($newFilename);

            $cat->setFilename($newFilename);
            $cat->setDateCreated(new \DateTime());
            $cat->setIsSold(false);

            $em->persist($cat);
            $em->flush();

            $event = new CatCreatedEvent($cat);
            $dispatcher->dispatch($event);
        }

        return $this->render('admin/cat_create.html.twig', [
            'catForm' => $catForm->createView()
        ]);
    }
}
