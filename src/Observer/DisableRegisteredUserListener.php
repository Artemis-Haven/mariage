<?php

namespace App\Observer;

use FOS\UserBundle\Event\GetResponseUserEvent;

class DisableRegisteredUserListener
{
    public function disableUser(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        $user->setEnabled(false);
    }
}
