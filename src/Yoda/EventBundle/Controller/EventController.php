<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Yoda\EventBundle\Entity\Event;
use Yoda\EventBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Event controller.
 *
 */
class EventController extends Controller
{

    /**
     * @Route("/", name="event")
     * @Template()
     * Lists all Event entities.
     *
     */
    public function indexAction()
    {
        return array();
    }
    
    public function _upcomingEventsAction($max = null)
    {
        $em = $this->getDoctrine()->getManager();
        
        $events = $em->getRepository('EventBundle:Event')
            ->getUpcomingEvents($max);
        
        return $this->render('EventBundle:Event:_upcomingEvents.html.twig', array(
            'events' => $events,
        ));
    }
    
    /**
     * Creates a new Event entity.
     *
     */
    public function createAction(Request $request)
    {
        $this->enforceUserSecurity('ROLE_EVENT_CREATE');
        
        $entity = new Event();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setOwner($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('event_show', array('slug' => $entity->getSlug())));
        }
        
        $this->enforceOwnerSecurity($entity);

        return $this->render('EventBundle:Event:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('event_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Event entity.
     *
     */
    public function newAction()
    {
        $this->enforceUserSecurity('ROLE_EVENT_CREATE');
        
        $entity = new Event();
        $form   = $this->createCreateForm($entity);

        return $this->render('EventBundle:Event:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Event entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EventBundle:Event')
            ->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('EventBundle:Event:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction($id)
    {
        $this->enforceUserSecurity();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EventBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        
        $this->enforceOwnerSecurity($entity);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EventBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('event_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Event entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $this->enforceUserSecurity();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EventBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        
        $this->enforceOwnerSecurity($entity);
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('event_edit', array('id' => $id)));
        }

        return $this->render('EventBundle:Event:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Event entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $this->enforceUserSecurity();
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EventBundle:Event')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }
            
            $this->enforceOwnerSecurity($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('event'));
    }

    /**
     * Creates a form to delete a Event entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    public function attendAction($id, $format)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($id);
        
        if(!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        
        if(!$event->hasAttendee($this->getUser())) {
            $event->getAttendees()->add($this->getUser());
        }
        $em->persist($event);
        $em->flush();
        
        return $this->createAttendingResponse($event, $format);
    }
    
    public function unattendAction($id, $format)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($id);
        
        if(!$event) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        
        if($event->hasAttendee($this->getUser())) {
            $event->getAttendees()->removeElement($this->getUser());
        }
        $em->persist($event);
        $em->flush();
        
        return $this->createAttendingResponse($event, $format);
    }
          
    
    private function enforceUserSecurity($role = 'ROLE_USER') {
        if (!$this->getSecurityContext()->isGranted($role)) {
            throw new AccessDeniedException('Need '.$role);
        }
    }
    
    private function createAttendingResponse(Event $event, $format)
    {
        if ($format == 'json') {
            $data = array(
                'attending' => $event->hasAttendee($this->getUser()),
            );
            $response = new JsonResponse($data);
            
            return $response;
        }
        
        $url = $this->generateUrl('event_show', array(
            'slug' => $event->getSlug()
        ));
        
        return $this->redirect($url);
    }
    
    
}
