<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\Backend\UserUpdateProfilType;
use App\Form\Backend\TechUpdateProfilType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class UserController extends AbstractController
{

    /**
     * @Route("backend/user/index", name="backend_users_list")
     */
    public function showlist(UserRepository $userRepository, Request $request, PaginatorInterface $paginator)
    {
        $users = $paginator->paginate($userRepository->findAllMemberOnly(),
        $request->query->getInt('page', 1), 8
    );
        //dump($users);
       
        $user = new User();
        $form = $this->createForm(UserUpdateProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été correctement intégré!'
            );

            return $this->redirectToRoute('backend_users_list');
        }
        return $this->render('backend/user/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/backend/user/edit/{id}", name="backend_user_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, User $user = null)
    {
        
   
        $form = $this->createForm(UserUpdateProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été correctement modifié!'
            );

            return $this->redirectToRoute('backend_users_list'); 
        }

    
        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    

    /**
     * @Route("backend/user/delete/{id}",
     *     name="backend_user_delete",
     *     requirements={"id"="\d+"},
     *     methods={"POST"})
     */
    public function delete(Request $request, User $user = null)
    {
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_users_list');
    }

    /**
     * @Route("backend/allusers/index", name="backend_allusers_list")
     */
    public function showalllist(UserRepository $userRepository, Request $request, PaginatorInterface $paginator)
    {
        $users = $paginator->paginate($userRepository->findAll(),
        $request->query->getInt('page', 1), 10
    );
       
        //$clients= $userRepository->findByTechnicien($this->getUser()->getEmail());

        $user = new User();
        $form = $this->createForm(UserUpdateProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a été correctement intégré!'
            );

            return $this->redirectToRoute('backend_users_list');
        }
        return $this->render('backend/allusers/index.html.twig', [
            'users' => $users,
            //'clients' => $clients,
            'form' => $form->createView()
        ]);

    }

    

    
}
