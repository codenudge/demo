<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cartRepository;
    private $productRepository;
    private $session;

    public function __construct(
        CartRepository $cartRepository, 
        ProductRepository $productRepository,
        SessionInterface $session
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    /**
     * @Route("/cart", name="cart_view")
     */
    public function viewCart()
    {
        $sessionId = $this->session->getId() ?: 'default_session';

        $cartData = $this->cartRepository->findCartBySessionId($sessionId);
        if (empty($cartData)) {
            return $this->render('cart/view.html.twig', [
                'items' => [],
                'total' => 0,
            ]);
        }

        $cart = $cartData[0];
        $items = json_decode($cart['items'], true);

        $products = [];
        $total = 0;
        foreach ($items as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->getPrice() * $quantity,
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/view.html.twig', [
            'items' => $products,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function addToCart($id, Request $request)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            $this->addFlash('error', 'Product not found!');
            return $this->redirectToRoute('product_list');
        }

        $quantity = $request->query->get('quantity', 1);

        if (!is_numeric($quantity)) {
            $quantity = 1;
        }

        $sessionId = $this->session->getId() ?: 'default_session';

        $this->cartRepository->addItemToCart($sessionId, $id, $quantity);

        $this->addFlash('success', 'Product added to cart!');
        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function removeFromCart($id)
    {
        $sessionId = $this->session->getId();

        $cart = $this->cartRepository->findOneBy(['sessionId' => $sessionId]);

        if ($cart) {
            $items = $cart->getItems();

            unset($items[$id]);

            $cart->setItems($items);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cart);
            $entityManager->flush();
        }
        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart/clear", name="cart_clear")
     */
    public function clearCart()
    {
        $sessionId = $this->session->getId();
        $cart = $this->cartRepository->findOneBy(['sessionId' => $sessionId]);

        if ($cart) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_list');
    }
}
