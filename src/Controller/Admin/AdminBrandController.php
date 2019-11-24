<?php


namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use App\Service\Basket\BasketService;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBrandController extends AbstractController
{
    /**
     * @var BrandRepository
     */
    private $brandRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(BrandRepository $brandRepository, ObjectManager $em)
    {
        $this->brandRepository = $brandRepository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/brand", name="admin.brand.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BasketService $basketService)
    {
        $brands = $this->brandRepository->findAll();
        return $this->render('admin/brand/index.html.twig', [
            "current_menu" => 'brands',
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'brands' => $brands
        ]);
    }


    /**
     * @Route("/admin/brand/create", name="admin.brand.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, FileUploader $fileUploader, BasketService $basketService)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandFile = $form['image']->getData();
            if ($brandFile) {
                $brandFileName = $fileUploader->upload($brandFile);
                $brand->setImage($brandFileName);
            }
            $this->em->persist($brand);
            $this->em->flush();
            $this->addFlash('success', 'La nouvelle marque a été crée avec succès');
            return $this->redirectToRoute('admin.brand.index');
        }

        return $this->render('admin/brand/new.html.twig', [
            "current_menu" => 'brands',
            'brand' => $brand,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/brand/{id}", name="admin.brand.edit", methods="GET|POST")
     * @param Brand $brand
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Brand $brand, Request $request, FileUploader $fileUploader, BasketService $basketService )
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandFile = $form['image']->getData();
            if ($brandFile) {
                $brandFileName = $fileUploader->upload($brandFile);
                $brand->setImage($brandFileName);
            }
            $this->em->flush();
            $this->addFlash('success', 'La marque a été modifiée avec succès');
            return $this->redirectToRoute('admin.brand.index');
        }

        return $this->render('admin/brand/edit.html.twig', [
            "current_menu" => 'brands',
            'brand' => $brand,
            'items' => $basketService->getFullCart(),
            'total' => $basketService->getTotal(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/brand/{id}", name="admin.brand.delete", methods="DELETE")
     * @param Brand $brand
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function delete(Brand $brand, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $brand->getId(), $request->get('_token'))) {
            $this->em->remove($brand);
            $this->em->flush();
            $this->addFlash('success', 'Cette marque a bien été supprimée');
            return $this->redirectToRoute('admin.brand.index');
        }

    }
}