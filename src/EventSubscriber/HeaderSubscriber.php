<?php

namespace App\EventSubscriber;

use App\Repository\MagasinRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;


class HeaderSubscriber implements EventSubscriberInterface
{
    /** @var MagasinRepository */
    private $magasinRepository;

    /** @var \Twig\Environment */
    private $twig;

    public function __construct(MagasinRepository $magasinRepository, \Twig\Environment $twig)
    {
        $this->magasinRepository = $magasinRepository;
        $this->twig = $twig;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $shops = $this->magasinRepository->findAllShops();
        //dd($shops);
        $this->twig->addGlobal('teolshops', $shops);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
