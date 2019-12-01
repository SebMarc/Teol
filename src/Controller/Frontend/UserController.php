<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Form\UserUpdateProfilType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user/profil", name="user_profil", methods={"GET"})
     * @throws UnauthorizedHttpException when user member is not logged in
     */
    public function profil(UserRepository $ur, User $user = null)
    {
        
        if (!$user = $this->getUser()) {
            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        //$technicien = $user->getTechnicien();
        //dd($technicien);

        return $this->render('frontend/user/profil.html.twig', [
            'user' => $user,
            //'technicien' => $technicien
        
        ]);
    }

    /**
     * @Route("/user/update", name="user_profil_update", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws UnauthorizedHttpException when the user is not logged in
     */
    public function profileUpdate(Request $request)
    {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
        $updateForm = $this->createForm(UserUpdateProfilType::class, $user);
        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash(
                    'success',
                    'Votre modification de profil a bien été enregistrée'
            );

            return $this->redirectToRoute('user_profil');
        }

        return $this->render('frontend/user/user_profil_update.html.twig', [
            'update_form' => $updateForm->createView()
        ]);
    }

  
}
