<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\UserBundle\Model\UserManagerInterface;
use App\Entity\Gift;
use App\Entity\User;

class AdminController extends EasyAdminController
{
    private $userManager;

    private $mailer;
    
    public function __construct(UserManagerInterface $userManager, \Swift_Mailer $mailer)
    {
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    public function createNewUserEntity()
    {
        return $this->userManager->createUser();
    }

    public function persistUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateUserEntity($user)
    {
        $this->userManager->updateUser($user, false);
        parent::updateEntity($user);
    }

    

    public function persistGiftEntity($gift)
    {
        $gift->setCreatedAt(new \DateTime('now'));
        parent::persistEntity($gift);
    }

    
    public function emailWhenActivatedAction()
    {
        // change the properties of the given entity and save the changes
        $id = $this->request->query->get('id');
        $user = $this->em->getRepository(User::class)->find($id);
        
        $email = (new \Swift_Message("Activation de votre compte sur mariage-manon-remi.fr !"))
            ->setFrom($this->getParameter('mail_from'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/activation.html.twig', 
                    [
                        'user' => $user
                    ]
                ),
                'text/html'
            );
        
        $this->mailer->send($email);
        
        $this->addFlash('success', "Un e-mail de notification d'activation a été envoyé à ".$user->getEmail());

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ));
    }

    

    public function persistCeremonyGuestWaitingEntity($guest)
    {
        $this->persistCeremonyGuest($guest);
        parent::persistEntity($guest);
    }

    public function persistCeremonyGuestWhoRepliedEntity($guest)
    {
        $this->persistCeremonyGuest($guest);
        parent::persistEntity($guest);
    }

    private function persistCeremonyGuest($guest)
    {
        $guest->setAttendMeal(false);
        $guest->setAttendBrunch(false);
        $guest->setVeggie(false);
        $guest->setInvitedForCeremonyOnly(true);
    }

    public function persistFullGuestWaitingEntity($guest)
    {
        $guest->setInvitedForCeremonyOnly(false);
        parent::persistEntity($guest);
    }

    public function persistFullGuestWhoRepliedEntity($guest)
    {
        $guest->setInvitedForCeremonyOnly(false);
        parent::persistEntity($guest);
    }
    
}
