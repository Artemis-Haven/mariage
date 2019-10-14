<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\UserBundle\Model\UserManagerInterface;
use App\Entity\Gift;

class AdminController extends EasyAdminController
{
    private $userManager;
    
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
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
}