<?php


namespace App\Controller;


use App\Entity\Users;
use App\Form\UserInfosType;
use App\Repository\OrdersRepository;
use App\Repository\UsersRepository;
use App\Service\Basket\BasketService;
use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use Mpdf\Mpdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * @var OrdersRepository
     */
    private $orderRepository;


    public function __construct(UsersRepository $usersRepository, OrdersRepository $orderRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route(path="/profil", name="profil.index")
     * @return Response
     */
    public function index(BasketService $basketService): Response
    {
        $this->getUser()->getId();
        $orders = $this->orderRepository->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('profil/index.html.twig', [
            "current_menu" => 'user',
            'user' => $this->getUser(),
            'orders' => $orders,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profil.edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Users $user, Request $request, BasketService $basketService)
    {
        $form = $this->createForm(UserInfosType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'votre profil a été modifié avec succès');
            return $this->redirectToRoute('profil.index');
        }

        return $this->render('profil/edit.html.twig', [
            "current_menu" => 'user',
            'user' => $user,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/visualisation-de-la-commande/{reference}", name="profil.show.order", methods="GET")
     * @throws \Mpdf\MpdfException
     */
    public function showOrder(string $reference, OrdersRepository $ordersRepository, Request $request, BasketService $basketService)
    {
        $order = $ordersRepository->findOneBy(['reference' => $reference]);
        if($order) {
            $response = $this->render('order/showOrder.html.twig', [
                'host' => $request->getHost(),
                'order' => $order,
                'productsOrders' => $order->getProductsOrder(),
                'items' => $basketService->getFullCart(),
                'user' => $this->getUser()
            ]);
        return new Response($response->getContent());
        }
    }

    /**
     * @Route("/profil/facture/{reference}", name="profil.facture", methods="GET")
     * @throws \Mpdf\MpdfException
     */
    public function displayFacture(string $reference, OrdersRepository $ordersRepository, Request $request)
    {
        $order = $ordersRepository->findOneBy(['reference' => $reference]);
        if($order) {
            $response = $this->render('facture/facture.html.twig', [
                'host' => $request->getHost(),
                'order' => $order,
                'productsOrders' => $order->getProductsOrder(),
                'user' => $this->getUser()
            ]);

            // instantiate and use the dompdf class
            $dompdf = new Dompdf();
            $dompdf->loadHtml($response->getContent());

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream();

            return new Response($dompdf->outputHtml());
        }
    }
}