<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class CartRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Cart::class);
        $this->entityManager = $entityManager;
    }

    public function findCartBySessionId($sessionId)
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT * FROM cart WHERE session_id = "' . $sessionId . '"';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function saveCart($sessionId, $items)
    {
        $cart = $this->findOneBy(['sessionId' => $sessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($sessionId);
        }

        $cart->setItems($items);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }

    public function addItemToCart($sessionId, $productId, $quantity)
    {
        $cart = $this->findOneBy(['sessionId' => $sessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($sessionId);
        }

        $items = $cart->getItems();
        $items[$productId] = $quantity;
        $cart->setItems($items);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }

    public function clearAllCarts()
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'DELETE FROM cart';
        $stmt = $conn->prepare($sql);

        $stmt->executeQuery();
    }
}
