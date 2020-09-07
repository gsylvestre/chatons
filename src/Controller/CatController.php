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
        $cats = $catRepository->findBy(['isSold' => false], ['dateCreated' => 'DESC'], 100);

        return $this->render('cat/home.html.twig', [
            'cats' => $cats
        ]);
    }

    /**
     * @Route("/details/{id}", name="cat_detail")
     */
    public function detail(CatRepository $catRepository, int $id)
    {
        $cat = $catRepository->find($id);
        if(!$cat){
            throw $this->createNotFoundException('Chat perdu !');
        }

        return $this->render('cat/detail.html.twig', [
            'cat' => $cat
        ]);
    }
}
