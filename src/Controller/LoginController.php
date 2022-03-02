<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Student;

class LoginController extends AbstractController
{
    /**
     * @Route("admin/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $session = new Session();
        if ($this->getUser()) {            
            if ($this->getUser()->getRoles() == ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SONATA_ADMIN']) {
                return $this->redirectToRoute('home');
            } elseif ($this->getUser()->getRoles() == ['ROLE_USER', 'ROLE_STUDENT']) {
                $session->set('id', $this->getUser()->getStudent()->getId());
                return $this->redirectToRoute('logged_in_student_details');
            }
            
            
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return new Response("Logged Out");
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
