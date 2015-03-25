<?php

namespace Yoda\EventBundle\Controller;

class DefaultController extends Controller
{
    public function indexAction($count, $firstName)
    {
        //$em = $this->container->get('doctrine')->getManager();
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('EventBundle:Event');
        
        $event = $repo->findOneBy(array(
            'name' => 'Darth\'s surprise birthday party!'
        ));
        
        if (!$event) {
            throw new \Doctrine\ORM\EntityNotFoundException();
        }
        
        return $this->render(
                'EventBundle:Default:index.html.twig',
                array(
                    'name' => $firstName,
                    'count' => $count,
                    'event' => $event,
                )
            );
    }
}
