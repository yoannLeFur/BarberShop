<?php


namespace App\Controller;


use App\Entity\Users;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('profil/index.html.twig', [
            "current_menu" => 'user',
            'user' => $this->getUser()
        ]);
    }
}