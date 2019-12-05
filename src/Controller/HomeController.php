<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Service\Basket\BasketService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(ProductRepository $productRepository, BrandRepository $brandRepository)
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * @Route(name="home.index",path="/")
     * @return Response
     */
    public function index(BasketService $basketService): Response
    {

        $products = $this->productRepository->findLatest();
        $brands = $this->brandRepository->findAll();
        return $this->render('pages/home.html.twig', [
            "current_menu" => 'last_products',
            "brands" => $brands,
            "products" => $products,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal()
        ]);
    }

}