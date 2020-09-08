<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Form\CatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function catCreate(Request  $request)
    {
        $cat = new Cat();
        $catForm = $this->createForm(CatType::class, $cat);

        $catForm->handleRequest($request);

        if ($catForm->isSubmitted() && $catForm->isValid()){

        }

        return $this->render('admin/cat_create.html.twig', [
            'catForm' => $catForm->createView()
        ]);
    }
}
