<?php


namespace App\Controller;


use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{

    /**
     * @Route("/panier", name="basket.index")
     */
    public function index(BasketService $basketService)
    {
        return $this->render('basket/index.html.twig', [
            "current_menu" => 'basket',
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal()
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="basket.add")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add($id, BasketService $basketService)
    {
        $basketService->add($id);
        return $this->redirectToRoute("basket.index");
    }

    /**
     * @Route("/panier/delete/{id}", name="basket.delete")
     */
    public function remove($id, BasketService $basketService)
    {
        $basketService->remove($id);
        return $this->redirectToRoute("basket.index");
    }
}