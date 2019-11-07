<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{

    /**
     * @Route(path="/panier", name="basket.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('basket/index.html.twig', [
            "current_menu" => 'basket'

        ]);
    }
}