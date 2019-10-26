<?php


namespace App\Controller\Admin;


use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Property;
use App\Form\ProductType;
use App\Form\PropertyType;
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
     * @Route("/admin/product", name="admin.product.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $products = $this->productRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            "current_menu" => 'products',
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
            $this->addFlash('success', 'Le nouveau produit a été crée avec succès');
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/new.html.twig', [
            "current_menu" => 'products',
            'property' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/{id}", name="admin.product.edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Product $product, Request $request)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Le produit a été modifié avec succès');
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/edit.html.twig', [
            "current_menu" => 'products',
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

}