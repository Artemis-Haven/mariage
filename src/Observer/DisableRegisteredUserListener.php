<?php

namespace App\Observer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;

class DisableRegisteredUserListener
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
    
    /**
     * During registration : disable the new user
     */
    public function onFosuserRegistrationInitialize(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        $user->setEnabled(false);
    }

    /**
     * After registration is completed : redirect the user
     */
    public function onFosuserRegistrationSuccess(FormEvent $event)
    {
        $url = $this->router->generate('waiting_for_validation');
        $event->setResponse(new RedirectResponse($url));
    }
}
