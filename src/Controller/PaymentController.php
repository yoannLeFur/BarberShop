<?php


namespace App\Controller;


use App\Entity\Users;
use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{

    /**
     * @Route("/payment", name="payment.index")
     */
    public function index(BasketService $basketService)
    {
        return $this->render('payment/payment.html.twig', [
            'user' => $this->getUser(),
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal()
        ]);
    }

    /**
     * @Route(path="/payment/validate", name="payment.validate")
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentAction(BasketService $basketService, Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_VNCNdxiNWEyC6cTDUehVhcEo00X56XFhfg');

        \Stripe\Customer::create([
            'description' => 'client',
            'email' => $this->getUser()->getUsername()
        ]);

        $charge = \Stripe\Charge::create([
            'amount' => $basketService->getTotal() * 100,
            'source' => $request->request->get('stripeToken'),
            'currency' => 'eur',
            'description' => 'client de mon site BarberShop'
        ]);


        return $this->render('basket/viewOrder.html.twig', [
            'charge' => $charge->values(),
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
        ]);
    }
}