<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


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
    public function clientslist(UserRepository $userRepository, Request $request, PaginatorInterface $paginator) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        dump($user);
        $clients= $paginator->paginate($userRepository->findAllClientByTechnicien($this->getUser()->getEmail()),
        $request->query->getInt('page', 1), 15
    );
        dump($clients);

        return $this->render('frontend/tech/tech_clients_index.html.twig', [
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/tech/order/index", name="tech_orders_index")
     */
    public function orderslist(CommandeRepository $cr, Request $request, PaginatorInterface $paginator) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        dump($user);
        $orders= $paginator->paginate($cr->findAllOrderByTechnicien($this->getUser()->getId()),
        $request->query->getInt('page', 1), 15
    );
        dump($orders);

        return $this->render('frontend/tech/tech_orders_index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/tech/client_last_orders/{id}", name="tech_client_last_orders", requirements={"id"="\d+"})
     */
    public function orderslistbyclient($id, CommandeRepository $cr, Request $request, PaginatorInterface $paginator) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        dump($user);
        $orders= $paginator->paginate($cr->findAllOrderPerOneClient($id),
        $request->query->getInt('page', 1), 15
    );
        dump($orders);

        return $this->render('frontend/tech/tech_client_last_orders.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/tech/encours/index", name="tech_encours_index")
     */
    public function encoursslist(UserRepository $ur, Request $request, PaginatorInterface $paginator) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        dump($user);
        $encours= $paginator->paginate($ur->findAllEncoursByTechnicien($this->getUser()->getEmail()),
        $request->query->getInt('page', 1), 15
    );
        dump($encours);

        return $this->render('frontend/tech/tech_encours_index.html.twig', [
            'encours' => $encours
        ]);
    }
}
