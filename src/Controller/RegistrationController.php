<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Knp\Component\Pager\PaginatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Classes;


// $session->get('name');

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
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

        $student = new Student();

        $user = new User();


        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setFirstName($form->get("student_first_name")->getData());
            $student->setLastName($form->get("student_last_name")->getData());
            
            
            $entityManager->persist($student);
            $user->setStudent($student);

            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER', 'ROLE_STUDENT']);
            // $user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SONATA_ADMIN']);
            $entityManager->persist($user);
            $entityManager->flush();
            $session->set('id', $student->getId());
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}
