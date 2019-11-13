<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProductRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route(name="home.index",path="/")
     * @return Response
     */
    public function index(): Response {

        $products = $this->repository->findLatest();
        return $this->render('pages/home.html.twig', [
            "current_menu" => 'products',
            "products" => $products
        ]);
    }

}