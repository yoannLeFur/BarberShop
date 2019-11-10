<?php


namespace App\Controller\Admin;


use App\Entity\Images;
use App\Entity\Product;
use App\Form\ImageType;
use App\Form\ProductType;
use App\Repository\ImagesRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminImageController extends AbstractController
{

    /**
     * @var ImagesRepository
     */
    private $imagesRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ImagesRepository $imagesRepository, ObjectManager $em)
    {
        $this->imagesRepository = $imagesRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/image", name="admin.image.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('admin/images/index.html.twig', [
            "current_menu" => 'images-admin',
        ]);
    }

    /**
     * @Route("/admin/images/create", name="admin.images.new")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, FileUploader $fileUploader)
    {
        $images = new Images();
        $form = $this->createForm(ImageType::class, $images);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form['image']->getData());
            $brochureFile = $form['image']->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $images->setImage($brochureFileName);
            }
            $this->em->persist($images);
            $this->em->flush();
            $this->addFlash('success', 'Le nouveau produit a été crée avec succès');
            return $this->redirectToRoute('admin.image.index');
        }

        return $this->render('admin/images/new.html.twig', [
            "current_menu" => 'products-admin',
            'product' => $images,
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
            "current_menu" => 'products-admin',
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

}