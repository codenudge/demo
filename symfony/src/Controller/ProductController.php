<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_list")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw new \Exception('Product not found!');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
