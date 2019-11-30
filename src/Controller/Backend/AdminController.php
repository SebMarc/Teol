<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function index()
    {
        return $this->render('backend/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
