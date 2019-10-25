<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrdersController extends AbstractController
{

    /**
     * @Route("/admin/commandes", name="admin.order.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('admin/orders/index.html.twig');
    }
}