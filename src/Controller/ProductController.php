<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
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
     * @Route(path="/boutique", name="product.index")
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $brands = $this->brandRepository->findAll();

        $products = $paginator->paginate(
            $this->productRepository->findAll(),
            $request->query->getInt('page', 1), 8
        );
        return $this->render('product/index.html.twig', [
            "current_menu" => 'products',
            "current_menu_brand" => 'brands',
            "brands" => $brands,
            "products" => $products
        ]);
    }

    /**
     * @Route(name="product.show", path="/boutique/{slug}-{id}", requirements={"slug": "[a-z0-9\-]*"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product, string $slug): Response
    {

        if($product->getSlug() !== $slug) {
            return $this->redirectToRoute('product.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ], 301);
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            "current_menu" => "products"
        ]);
    }

}