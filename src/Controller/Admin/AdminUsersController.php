<?php


namespace App\Controller\Admin;


use App\Repository\ProductRepository;
use App\Repository\UsersRepository;
use App\Service\Basket\BasketService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\B;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @Route("/admin/utilisateurs", name="admin.user.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BasketService $basketService)
    {
        $users = $this->usersRepository->findAll();
        return $this->render('admin/users/index.html.twig', [
            "current_menu" => 'users',
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'users' => $users
        ]);
    }

}