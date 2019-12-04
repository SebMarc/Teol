<?php

namespace App\Notification;

use App\Entity\Contact;
use App\Entity\TechContact;
use App\Entity\User;
use Twig\Environment;

class ContactNotification {

/**
 * @var \Swift_Mailer
 */
private $mailer;

 /**
 * @var Environment
 */
private $renderer;


    public function __construct(\Swift_Mailer $mailer, Environment $renderer){

            $this->mailer = $mailer;
            $this->renderer = $renderer;

    }

    public function notify(Contact $contact) {

        $message = (new \Swift_Message)
        ->setFrom($contact->getEmail())
        ->setTo('seb.marcaire@gmail.com')
        ->setReplyTo($contact->getEmail())
        ->setBody($this->renderer->render('frontend/email/contactsend.html.twig', [
            'contact' =>$contact
        ]), 'text/html');
        $this->mailer->send($message);
    }

    public function techcontact(User $user) {
        $message = (new \Swift_Message)
        ->setFrom($user->getEmail())
        ->setTo('seb.marcaire@gmail.com')
        ->setReplyTo($user->getEmail())
        ->setBody($this->renderer->render('frontend/email/techcontactsend.html.twig', [
            'user' => $user
        ]), 'text/html');
        $this->mailer->send($message);
    }

    public function membercontact(User $user) {
        $message = (new \Swift_Message)
        ->setFrom($user->getEmail())
        ->setTo('seb.marcaire@gmail.com')
        ->setReplyTo($user->getEmail())
        ->setBody($this->renderer->render('frontend/email/membercontactsend.html.twig', [
            'user' => $user
        ]), 'text/html');
        $this->mailer->send($message);
    }
}