<?php


namespace App\Controller;


use App\Entity\Users;
use App\Form\UserInfosType;
use App\Repository\OrdersRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
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

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UsersRepository $usersRepository, OrdersRepository $orderRepository, ObjectManager $em)
    {
        $this->usersRepository = $usersRepository;
        $this->orderRepository = $orderRepository;
        $this->em = $em;
    }

    /**
     * @Route(path="/profil", name="profil.index")
     * @return Response
     */
    public function index(): Response
    {
        $this->getUser()->getId();
        $orders = $this->orderRepository->findAll();
        return $this->render('profil/index.html.twig', [
            "current_menu" => 'user',
            'user' => $this->getUser(),
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profil.edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Users $user, Request $request)
    {
        $form = $this->createForm(UserInfosType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'votre profil a été modifié avec succès');
            return $this->redirectToRoute('profil.index');
        }

        return $this->render('profil/edit.html.twig', [
            "current_menu" => 'user',
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}