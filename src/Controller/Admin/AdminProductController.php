<?php


namespace App\Controller\Admin;


use App\Entity\Product;
use App\Entity\ProductsOrder;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\Basket\BasketService;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $productRepository;


    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/admin/product", name="admin.product.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BasketService $basketService)
    {
        $products = $this->productRepository->findAll();

        return $this->render('admin/product/index.html.twig', [
            "current_menu" => 'products-admin',
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            "products" => $products
        ]);
    }

    /**
     * @Route("/admin/product/create", name="admin.product.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, FileUploader $fileUploader, BasketService $basketService)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $brochureFile = $form['image']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $product->setImage($brochureFileName);
            }
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Le nouveau produit a été crée avec succès');
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/new.html.twig', [
            "current_menu" => 'products-admin',
            'product' => $product,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/{id}", name="admin.product.edit", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Product $product, Request $request, FileUploader $fileUploader, BasketService $basketService)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $brochureFile = $form['image']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $product->setImage($brochureFileName);
            }
            $em->flush();
            $this->addFlash('success', 'Le produit a été modifié avec succès');
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/edit.html.twig', [
            "current_menu" => 'products-admin',
            'product' => $product,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/{id}", name="admin.product.delete", methods="DELETE")
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function delete(ProductsOrder $productsOrder, Product $product, Request $request)
    {



//            dump($productsOrder->getProduct()->getId());
//            dd($product->getId());
        if ($productsOrder->getProduct()->getId() === $product->getId()) {
            $this->addFlash('danger', 'Ce produit ne peut pas être supprimer');
            return $this->redirectToRoute('admin.product.index');
        } else {

            if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($product);
                $em->flush();
                $this->addFlash('success', 'Ce produit a bien été supprimer');
                return $this->redirectToRoute('admin.product.index');
            }
        }
        return $this->redirectToRoute('admin.product.index');
    }

}