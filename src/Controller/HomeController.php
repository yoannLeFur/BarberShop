<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\BrandRepository;
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
    private $productRepository;

    /**
     * @var BrandRepository
     */
    private $brandRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProductRepository $productRepository, BrandRepository $brandRepository, ObjectManager $em)
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
        $this->em = $em;
    }

    /**
     * @Route(name="home.index",path="/")
     * @return Response
     */
    public function index(): Response {

        $products = $this->productRepository->findLatest();
        $brands = $this->brandRepository->findAll();
        return $this->render('pages/home.html.twig', [
            "current_menu" => 'products',
            "brands" => $brands,
            "products" => $products
        ]);
    }

}