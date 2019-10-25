<?php


namespace App\Controller\Admin;


use App\Repository\OrdersRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrdersController extends AbstractController
{
    /**
     * @var OrdersRepository
     */
    private $orderRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(OrdersRepository $orderRepository, ObjectManager $em)
    {
        $this->orderRepository = $orderRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/commandes", name="admin.order.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $orders = $this->orderRepository->findAll();
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders
        ]);
    }
}