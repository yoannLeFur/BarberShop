<?php


namespace App\Controller\Admin;


use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Form\ProductType;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProductRepository $productRepository, ObjectManager $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
    }


    /**
     * @Route("/admin", name="admin.base")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('admin/base.html.twig');
    }

    /**
     * @Route("/admin/product", name="admin.product.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allProducts()
    {
        $products = $this->productRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            "products" => $products
        ]);
    }

    /**
     * @Route("/admin/product/create", name="admin.product.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/new.html.twig', [
            'property' => $product,
            'form' => $form->createView()
        ]);
    }

}