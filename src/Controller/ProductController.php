<?php


namespace App\Controller;


use App\Entity\Product;
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
     * @Route(path="/boutique", name="product.index")
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $products = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1), 8
        );
        return $this->render('product/index.html.twig', [
            "current_menu" => 'products',
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
                'slug' => $product->getSlug()
            ], 301);
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            "current_menu" => "products"
        ]);
    }

}