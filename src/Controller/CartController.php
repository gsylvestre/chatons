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
        $cart = $cartRepository->find($id);
        if (!$cart){

        }

        return $this->render('cart/display.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/add/{id}", name="add", requirements={"id": "\d+"})
     */
    public function add(int $id, CartRepository $cartRepository, CatRepository $catRepository, EntityManagerInterface $em, TranslatorInterface $translator)
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
            $cart->setStatus("active");
        }

        $cat = $catRepository->find($id);
        $cartProduct = new CartProduct();
        $cartProduct->setProduct($cat);
        $cartProduct->setDateAdded(new \DateTime());

        $cart->addCartProduct($cartProduct);
        $em->persist($cart);
        $em->persist($cartProduct);
        $em->flush();

        $this->addFlash("success", $translator->trans('cat.added.to.cart'));
        return $this->redirectToRoute('home');
    }
}
