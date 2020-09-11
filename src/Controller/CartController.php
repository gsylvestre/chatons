<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartRepository;
use App\Repository\CatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{

    /**
     * @Route("/display/{id}", name="display", requirements={"id": "\d+"})
     */
    public function display(int $id, CartRepository $cartRepository, EntityManagerInterface $em)
    {
        //on devrait limiter l'accès aux users connectés ici

        //récupère le panier dont l'id est dans l'URL
        $cart = $cartRepository->find($id);
        if (!$cart){
            //@todo
        }

        //affiche le panier
        return $this->render('cart/display.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * Ajoute un item au panier. On reçoit l'id du chaton dans l'URL (aurait dû être fait en post)
     * @Route("/add/{id}", name="add", requirements={"id": "\d+"})
     */
    public function add(int $id, CartRepository $cartRepository, CatRepository $catRepository, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        //$this->denyAccessUnlessGranted() aurait pu être plus propre ici
        //empêche les utilisateurs non-connectés de venir ici
        if (!$this->getUser()){
            $this->addFlash('warning', 'Merci de vous connecter avant !');
            return $this->redirectToRoute('cat_detail', ['id' => $id]);
        }

        //récupère le panier
        $cart = $cartRepository->findOneBy(['user' => $this->getUser()]);
        //si on n'en trouve pas, on en crée un
        if (!$cart){
            $cart = new Cart();
            $cart->setUser($this->getUser());
            $cart->setDateCreated(new \DateTime());
            $cart->setStatus("active");
        }

        //retrouve le chaton à ajouter
        $cat = $catRepository->find($id);
        //crée un item de panier (un peu overkill ici)
        $cartProduct = new CartProduct();
        $cartProduct->setProduct($cat);
        $cartProduct->setDateAdded(new \DateTime());

        //ajoute l'item dans le panier
        $cart->addCartProduct($cartProduct);

        //sauvegarde tout ça en bdd
        $em->persist($cart);
        $em->persist($cartProduct);
        $em->flush();

        //redirige
        $this->addFlash("success", $translator->trans('cat.added.to.cart'));
        return $this->redirectToRoute('home');
    }
}
