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

class TechnicienController extends AbstractController
{
    /**
     * @Route("backend/tech/index", name="backend_techs_list")
     */
    public function showtechlist(UserRepository $techRepository, Request $request, PaginatorInterface $paginator)
    {
        $users = $paginator->paginate($techRepository->findAllTechnicien(),
        $request->query->getInt('page', 1), 5
        );

        $user = new User();
        $form = $this->createForm(UserUpdateProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le technicien a été correctement intégré!'
            );

            return $this->redirectToRoute('backend_techs_list');
        }
        return $this->render('backend/tech/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);

    }

     /**
     * @Route("/backend/tech/edit/{id}", name="backend_tech_edit", requirements={"id"="\d+"},)
     */
    public function techedit(Request $request, User $user = null)
    {
        if (!$user) {
            throw $this->createNotFoundException('Le technicien que vous recherchez n\'existe pas !');
        }
   
        $form = $this->createForm(TechUpdateProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le technicien a été correctement modifié!'
            );

            return $this->redirectToRoute('backend_techs_list'); 
        }

    
        return $this->render('backend/tech/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("backend/tech/delete/{id}",
     *     name="backend_tech_delete",
     *     requirements={"id"="\d+"},
     *     methods={"POST"})
     */
    public function techdelete(Request $request, User $user = null)
    {
        if (!$user) {
            throw $this->createNotFoundException('Le technicien que vous recherchez n\'existe pas !');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Le technicien a été supprimé avec succès !'
            );
        }
        else {

            $this->addFlash(
                'error',
                'Une erreur s\'est produite. Veuillez réessayer plus tard !'
            );
        }

        return $this->redirectToRoute('backend_techs_list');
    }

    
}
