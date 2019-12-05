<?php

namespace App\Controller\Frontend;

use App\Entity\Magasin;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shopview/{id<\d+>}", name="shop_view")
     */
    public function shopview($id, Magasin $mag, MagasinRepository $mr)
    {
        $mag = $mr->find($id);
        //dd($mag);
      

        return $this->render('frontend/magasin/shop_view.html.twig', [
            'shop' => $mag
        ]);
    }

    
}
