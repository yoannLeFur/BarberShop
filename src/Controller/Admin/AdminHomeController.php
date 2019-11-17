<?php


namespace App\Controller\Admin;


use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{

    /**
     * @var OrdersRepository
     */
    private $orderRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(OrdersRepository $orderRepository,ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/admin/accueil", name="admin.home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $products = $this->productRepository->findLatest();
        $orders = $this->orderRepository->findAll();
        return $this->render('admin/home.html.twig', [
            "current_menu" => 'home',
            'products' => $products,
            'orders' => $orders
        ]);
    }
}