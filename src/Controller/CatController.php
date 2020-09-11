<?php

namespace App\Controller;

use App\Repository\CatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CatController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(CatRepository $catRepository)
    {
        //récupère les chatons non vendus
        $cats = $catRepository->findBy(['isSold' => false], ['dateCreated' => 'DESC'], 100);

        //les affiche
        return $this->render('cat/home.html.twig', [
            'cats' => $cats
        ]);
    }

    /**
     * @Route("/details/{id}", name="cat_detail")
     */
    public function detail(CatRepository $catRepository, int $id)
    {
        //récupère le chaton à afficher
        $cat = $catRepository->find($id);
        //404 si on le trouve pas
        if(!$cat){
            throw $this->createNotFoundException('Chat perdu !');
        }

        //affiche le chaton
        return $this->render('cat/detail.html.twig', [
            'cat' => $cat
        ]);
    }
}
