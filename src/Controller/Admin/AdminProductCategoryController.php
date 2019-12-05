<?php


namespace App\Controller\Admin;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use App\Service\Basket\BasketService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductCategoryController extends AbstractController
{
    /**
     * @var ProductCategoryRepository
     */
    private $categoryRepository;


    public function __construct(ProductCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @Route("/admin/category", name="admin.category.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BasketService $basketService)
    {
        $categories = $this->categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', [
            "current_menu" => 'categories',
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/admin/category/create", name="admin.category.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, BasketService $basketService)
    {
        $category = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La nouvelle catégorie a été crée avec succès');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/new.html.twig', [
            "current_menu" => 'categories',
            'category' => $category,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="admin.category.edit", methods="GET|POST")
     * @param ProductCategory $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(ProductCategory $category, Request $request, BasketService $basketService)
    {
        $form = $this->createForm(ProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'La catégorie a été modifiée avec succès');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            "current_menu" => 'categories',
            'category' => $category,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="admin.category.delete", methods="DELETE")
     * @param ProductCategory $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function delete(ProductCategory $category, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Cette catégorie a bien été supprimée');
            return $this->redirectToRoute('admin.category.index');
        }

    }
}