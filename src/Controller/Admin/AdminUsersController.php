<?php


namespace App\Controller\Admin;


use App\Repository\ProductRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $usersRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UsersRepository $usersRepository, ObjectManager $em)
    {
        $this->usersRepository = $usersRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/utilisateurs", name="admin.user.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $users = $this->usersRepository->findAll();
        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }

}