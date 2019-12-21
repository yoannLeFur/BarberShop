<?php


namespace App\Controller;


use App\Entity\Orders;
use App\Entity\PaymentMethod;
use App\Entity\ProductsOrder;
use App\Entity\Users;
use App\Repository\OrdersRepository;
use App\Repository\PaymentMethodRepository;
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
    public function paymentAction(BasketService $basketService, Request $request, OrdersRepository $ordersRepository, PaymentMethodRepository $paymentMethodRepository)
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

        $em = $this->getDoctrine()->getManager();
        $paymenntMethod = $paymentMethodRepository->findOneBy(['name' => 'CB']);
        $order = new Orders();

        $order
            ->setUser($this->getUser())
            ->setReference('REF_' . uniqid())
            ->setDate(new \DateTime())
            ->setTotalPrice($basketService->getTotal())
            ->setPaymentMethod($paymenntMethod);

        foreach ($basketService->getFullCart() as $product) {
            for ($i = 0; $i < $product['quantity']; $i++) {
                $productOrders = new ProductsOrder();
                $productOrders
                    ->setOrders($order)
                    ->setProduct($product['product']);
                $order->addProductsOrder($productOrders);
            }
        }

        $em->persist($order);
        $em->flush();


        return $this->render('payment/viewOrder.html.twig', [
            'charge' => $charge->values(),
            'items' => $basketService->clear(),
            'user' => $this->getUser(),
            'productsOrders' => $order->getProductsOrder(),
            'order' => $order
        ]);
    }
}