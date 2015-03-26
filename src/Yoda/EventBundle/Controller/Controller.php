<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Yoda\EventBundle\Entity\Event;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
 * Description of Controller
 *
 * @author tom
 */
class Controller extends BaseController
{
    /**
     * 
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->container->get('security.context');
    }
    
    public function enforceOwnerSecurity(Event $event)
    {
        $user = $this->getUser();
        
        if ($user != $event->getOwner()) {
            throw new AccessDeniedException('you do not own this!');
        }
    }
}
