<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Entity\Visite;
use App\Form\VisiteFormType;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use App\Repository\VisiteRepository;
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
        //dump($user);

        $searchSociety = $request->request->get('society');
        $searchLastname = $request->request->get('lastname');

        if($searchSociety) {
            $clients = $paginator->paginate(
                $userRepository->findByPartialSocietyAllClientByTechnicien($searchSociety, $user->getEmail()),
                $request->query->getInt('page', 1),8
        );
        }
        elseif ($searchLastname) {
            $clients = $paginator->paginate(
                $userRepository->findByPartialLastnameAllClientByTechnicien($searchLastname, $user->getEmail()),
                $request->query->getInt('page', 1),
                8
            );} 
        
        else {
            $clients= $paginator->paginate(
            $userRepository->findAllClientByTechnicien($this->getUser()->getEmail()),
            $request->query->getInt('page', 1),
            15
        );
        }
        dump($clients);

        return $this->render('frontend/tech/tech_clients_index.html.twig', [
            'clients' => $clients,
          

        ]);
    }

    /**
     * @Route("/tech/order/index", name="tech_orders_index")
     */
    public function orderslist(CommandeRepository $cr, Request $request, PaginatorInterface $paginator) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }

        $searchorderNumber = $request->request->get('ordernumber');
        $searchMember = $request->request->get('member');

        if ($searchorderNumber) {
            $orders = $paginator->paginate(
                $cr->findByOrderNumber($searchorderNumber, $this->getUser()->getId()),
                $request->query->getInt('page', 1),
                8
            );}
            elseif ($searchMember) {
                $orders = $paginator->paginate(
                    $cr->findByMember($searchMember,$this->getUser()->getId()),
                    $request->query->getInt('page', 1),
                    8
                );}
            else {
                $orders= $paginator->paginate(
                $cr->findAllOrderByTechnicien($this->getUser()->getId()),
                $request->query->getInt('page', 1),
                15
            );
            }

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

        $searchSociety = $request->request->get('society');
        
        if($searchSociety) {
            $encours = $paginator->paginate(
                $ur->findByPartialSocietyAllSocietyByTechnicien($searchSociety, $user->getEmail()),
                $request->query->getInt('page', 1),15
        );
        }else {
            $encours= $paginator->paginate(
            $ur->findAllEncoursByTechnicien($this->getUser()->getEmail()),
            $request->query->getInt('page', 1),
            15
        );
        }
        //dump($encours);

        return $this->render('frontend/tech/tech_encours_index.html.twig', [
            'encours' => $encours
        ]);
    }

     /**
     * @Route("/tech/visites/index", name="tech_visites_index")
     */
    public function visiteslist(UserRepository $userRepository, Request $request, PaginatorInterface $paginator, VisiteRepository $vr) {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        //dump($user);

        $searchSociety = $request->request->get('society');
        $searchDate = $request->request->get('date');

        if($searchSociety) {
            $visites = $paginator->paginate(
                $vr->findByPartialSocietyVisiteByTechnicien($searchSociety, $user->getId()),
                $request->query->getInt('page', 1),8
        );
        }
        elseif ($searchDate) {
            $visites = $paginator->paginate(
                $userRepository->findByDateVisiteByTechnicien($searchDate, $user->getId()),
                $request->query->getInt('page', 1),
                8
            );} 
        
        else {
            $visites = $paginator->paginate(
                $vr->findAllVisiteByTechnicien($this->getUser()->getId()),
                $request->query->getInt('page',1),
                15
            );
        }
        
      
        $visite = new Visite();
        $form = $this->createForm(VisiteFormType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visite->setTech($this->getUser());
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($visite);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre visite a été correctement intégrée!'
            );

            return $this->redirectToRoute('tech_visites_index');   
        }

        return $this->render('frontend/tech/tech_visites_index.html.twig', [

            'visites' => $visites,
            'form' => $form->createView(),
            

        ]);
    }
}
