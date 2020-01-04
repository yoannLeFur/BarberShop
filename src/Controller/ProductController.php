<?php


namespace App\Controller;


use App\Entity\Product;
use App\Entity\ProductSearch;
use App\Form\ProductSearchType;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Service\Basket\BasketService;
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


    public function __construct(ProductRepository $productRepository, BrandRepository $brandRepository)
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * @Route(path="/boutique", name="product.index")
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator, BasketService $basketService): Response
    {
        $search = new ProductSearch();
        $form = $this->createform(ProductSearchType::class, $search);
        $form->handleRequest($request);

        $products = $paginator->paginate(
            $this->productRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 8
        );
        return $this->render('product/index.html.twig', [
            "current_menu" => 'products',
            "current_menu_brand" => 'brands',
            "products" => $products,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(name="product.show", path="/boutique/{slug}-{id}", requirements={"slug": "[a-z0-9\-]*"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product, string $slug, BasketService $basketService): Response
    {

        if($product->getSlug() !== $slug) {
            return $this->redirectToRoute('product.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ], 301);
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            "current_menu" => "products"
        ]);
    }

}