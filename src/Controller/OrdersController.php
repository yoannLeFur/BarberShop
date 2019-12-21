<?php


namespace App\Controller;


use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
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
     * @Route(name="order.show", path="/profil/commandes/{slug}-{id}", requirements={"slug": "[a-z0-9\-]*"})
     * @param Orders $order
     * @return Response
     */
    public function show(Orders $order, string $slug, BasketService $basketService): Response
    {
        if ($order->getSlug() !== $slug) {
            return $this->redirectToRoute('order.show', [
                'id' => $order->getUser(),
                'slug' => $order->getSlug(),
            ], 301);
        }
        return $this->render('order/show.html.twig', [
            'order' => $order,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            "current_menu" => "products"
        ]);
    }
}