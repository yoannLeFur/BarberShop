<?php


namespace App\Controller;


use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
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
     * @Route(name="order.show", path="/profil/commandes/{slug}-{id}", requirements={"slug": "[a-z0-9\-]*"})
     * @param Orders $order
     * @return Response
     */
    public function show(Orders $order, string $slug): Response
    {

        if($order->getSlug() !== $slug) {
            return $this->redirectToRoute('order.show', [
                'id' => $order->getId(),
                'slug' => $order->getSlug(),
            ], 301);
        }
        return $this->render('order/show.html.twig', [
            'order' => $order,
            "current_menu" => "products"
        ]);
    }
}