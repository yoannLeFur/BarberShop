<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route(path="/boutique", name="product.index")
     * @param ProductRepository $repository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findLatest();
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