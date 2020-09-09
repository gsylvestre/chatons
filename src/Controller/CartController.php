<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartRepository;
use App\Repository\CatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/add/{id}", name="add", requirements={"id": "\d+"})
     */
    public function add(int $id, CartRepository $cartRepository, CatRepository $catRepository, EntityManagerInterface $em)
    {
        if (!$this->getUser()){
            $this->addFlash('warning', 'Merci de vous connecter avant !');
            return $this->redirectToRoute('cat_detail', ['id' => $id]);
        }

        $cart = $cartRepository->findOneBy(['user' => $this->getUser()]);
        if (!$cart){
            $cart = new Cart();
            $cart->setUser($this->getUser());
            $cart->setDateCreated(new \DateTime());
            $cart->setStatus("new");
        }

        $cat = $catRepository->find($id);
        $cartProduct = new CartProduct();
        $cartProduct->setProduct($cat);
        $cartProduct->setDateAdded(new \DateTime());

        $cart->addCartProduct($cartProduct);
        $em->persist($cart);
        $em->persist($cartProduct);
        $em->flush();

        $this->addFlash("success", "Chaton ajouté au panier !");
        return $this->redirectToRoute('home');
    }
}
