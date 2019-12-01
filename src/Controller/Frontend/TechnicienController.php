<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;


class TechnicienController extends AbstractController
{
    /**
     * @Route("/tech/profil", name="tech_profil")
     * @throws UnauthorizedHttpException when user member is not logged in
     */
    public function techprofil()
    {
        if (!$user = $this->getUser()) {
            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        return $this->render('frontend/tech/profil.html.twig', [
            'tech' => $user
        ]);
    }

   

    /**
     * @Route("/tech/client/{id}", name="tech_client_view", requirements={"id"="\d+"})
     */
    public function view($id, UserRepository $ur) {

        $user = $ur->find($id);
        return $this->render('frontend/tech/tech_client_view.html.twig', [
            'user' => $user,
            
        ]);
    }

     /**
     * @Route("/tech/client/index", name="tech_clients_index")
     */
    public function clientlist(UserRepository $userRepository) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        dump($user);
        $clients= $userRepository->findAllClientByTechnicien($this->getUser()->getEmail());
        dump($clients);

        return $this->render('frontend/tech/tech_clients_index.html.twig', [
            'clients' => $clients
        ]);
    }
}
