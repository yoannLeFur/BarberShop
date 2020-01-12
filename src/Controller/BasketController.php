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
        $this->addFlash('success', 'Le produit a été ajouté au panier avec succès');
        return $this->redirectToRoute("product.index");
    }

    /**
     * @Route("/panier/addQuantity/{id}", name="basket.addQuantity")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addQuantity($id, BasketService $basketService)
    {
        $basketService->add($id);
        $this->addFlash('success', 'La quantié du produit a été modifiée au panier avec succès');
        return $this->redirectToRoute("basket.index");
    }


    /**
     * @Route("/panier/remove/{id}", name="basket.remove")
     */
    public function remove($id, BasketService $basketService)
    {
        $basketService->remove($id);
        $this->addFlash('danger', 'La quantié du produit a été modifiée au panier avec succès');
        return $this->redirectToRoute("basket.index");
    }

    /**
     * @Route("/panier/delete/{id}", name="basket.delete")
     */
    public function delete($id, BasketService $basketService)
    {
        $basketService->delete($id);
        $this->addFlash('danger', 'Le produit a été supprimer du panier avec succès');
        return $this->redirectToRoute("basket.index");
    }


    /**
     * @Route("/panier/payment", name="basket.payment")
     */
    public function payement(BasketService $basketService)
    {
        if (empty($basketService->getFullCart()) || $basketService->getFullCart() === null) {
            return $this->redirectToRoute("home.index");
        }

        return $this->render('basket/payment.html.twig', [
            'user' => $this->getUser(),
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal()
        ]);
    }
}