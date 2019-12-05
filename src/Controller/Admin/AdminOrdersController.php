<?php


namespace App\Controller\Admin;


use App\Repository\OrdersRepository;

use App\Service\Basket\BasketService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrdersController extends AbstractController
{
    /**
     * @var OrdersRepository
     */
    private $orderRepository;


    public function __construct(OrdersRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/admin/commandes", name="admin.order.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BasketService $basketService)
    {
        $orders = $this->orderRepository->findAll();
        return $this->render('admin/orders/index.html.twig', [
            "current_menu" => 'orders',
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'orders' => $orders
        ]);
    }
}