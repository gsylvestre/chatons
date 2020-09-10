<?php

namespace App\Tests;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Cat;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCartPriceTotalIsOK()
    {
        $cat = new Cat();
        $cat->setPrice(55);

        $cat2 = new Cat();
        $cat2->setPrice(77);

        $cp = new CartProduct();
        $cp->setProduct($cat);
        $cp2 = new CartProduct();
        $cp2->setProduct($cat2);

        $cart = new Cart();
        $cart->addCartProduct($cp);
        $cart->addCartProduct($cp2);

        $total = $cart->getCartTotal();
        $this->assertEquals(132, $total, 'cart total should be 132');
    }
}
