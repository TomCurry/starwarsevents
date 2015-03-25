<?php

namespace Yoda\UserBundle\Controller;

use Yoda\EventBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
Use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * Description of SecurityController
 *
 * @author tom
 */
class SecurityController extends Controller {
    /**
     * @Route("/login", name="login_form")
     * @Template
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            );
    }
    
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {
        //blank
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {
        //blank
    }
}
