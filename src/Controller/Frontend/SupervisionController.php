<?php

namespace App\Controller\Frontend;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupervisionController extends AbstractController
{
    /**
     * @Route("/supervision", name="supervision_index")
     */
    public function index()
    {
        return $this->render('frontend/supervision/index.html.twig', [
            'controller_name' => 'SupervisionController',
        ]);
    }

    /**
     * @Route("/supervision/encours/index", name="supervision_encours_index")
     */
    public function encoursindex(UserRepository $ur, PaginatorInterface $paginator, Request $request)
    {
        $searchSociety = $request->request->get('society');

        if ($searchSociety) {
            $encours = $paginator->paginate(
                $ur->findEncoursBySociety($searchSociety),
                $request->query->getInt('page', 1),
                15
            );
        }else {
            $encours= $paginator->paginate(
            $ur->findAllMemberOnlyGlobalEncours(),
            $request->query->getInt('page', 1),
            15
        );
        }
        //dump($encours);

        $globalencours = 0;
        $allMembers =  $ur->findAll();
       
        foreach($allMembers as $enc) {
            $globalencours += ($enc->getEncours());
        }
        dump($allMembers, $globalencours);

        return $this->render('frontend/supervision/supervision_encours_index.html.twig', [
            'encours' => $encours,
            'globalencours' => $globalencours
        ]);
    }

    /**
     * @Route("/supervision/techniciens/index", name="supervision_techniciens_index")
     */
    public function suptechindex() {
        return $this->render('frontend/supervision/supervision_techniciens_index.html.twig');
    }

    /**
     * @Route("/supervision/adherents/index", name="supervision_adherents_index")
     */
    public function supadhindex() {
        return $this->render('frontend/supervision/supervision_adherents_index.html.twig');
    }

    /**
     * @Route("/supervision/magasins/index", name="supervision_magasins_index")
     */
    public function supmagindex() {
        return $this->render('frontend/supervision/supervision_magasins_index.html.twig');
    }
}
