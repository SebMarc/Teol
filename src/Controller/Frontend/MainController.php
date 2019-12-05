<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Notification\ContactNotification;
use App\Form\ContactFormType;
use App\Entity\Contact;
use App\Form\TechContactFormType;
use App\Repository\MagasinRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MainController extends AbstractController
{
    
    /**
     * @Route("/",
     *  name="homepage")
     */
    public function index()
    {
        
        return $this->render('frontend/main/homepage.html.twig', [
            'controller_name' => 'MainController',
           
        ]);
    }

    /**
     * @Route("/contact",
     *     name="contact",
     *     methods={"GET", "POST"})
     *
     * @param Request $request
     *
     */
    public function contact(Request $request, ContactNotification $notification)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $manager= $this->getDoctrine()->getManager();
            $manager->persist($contact);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'email a été envoyé avec succès !'
            );

            return $this->redirectToRoute('homepage'); 
        }

        return $this->render('frontend/main/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/techcontact",
     *     name="techcontact",
     *     methods={"GET", "POST"})
     *
     * @param Request $request
     *
     */
    public function techcontact(Request $request, ContactNotification $notification)
    {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
       
        $form = $this->createForm(TechContactFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->techcontact($user);
           
            $this->addFlash(
                'success',
                'L\'email a été envoyé avec succès !'
            );

            return $this->redirectToRoute('homepage'); 
        }

        return $this->render('frontend/main/techcontact.html.twig', [
            'techcontactForm' => $form->createView(),
            'user' => $user,
            
        ]);
    }

    /**
     * @Route("/membercontact",
     *     name="membercontact",
     *     methods={"GET", "POST"})
     *
     * @param Request $request
     *
     */
    public function membercontact(Request $request, ContactNotification $notification)
    {
        if (!$user = $this->getUser()) {

            throw new UnauthorizedHttpException('', 'Vous devez d\'abord vous connectez pour accéder à cette page');
        }
       
        $form = $this->createForm(TechContactFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->membercontact($user);
           
            $this->addFlash(
                'success',
                'L\'email a été envoyé avec succès !'
            );

            return $this->redirectToRoute('homepage'); 
        }

        return $this->render('frontend/main/membercontact.html.twig', [
            'membercontactForm' => $form->createView(),
            'user' => $user,
            
        ]);
    }


    
}
