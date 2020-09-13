<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;



class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

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
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        if ($request->get('password') == $request->get('confirm_password')){
            if ($request->isMethod('POST')) {
                $user = new User();
                $user->setEmail($request->request->get('email'));
                $user->setLastname($request->request->get('lastname'));
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $user->setFirstname($request->request->get('firstname'));
                //$user->addRole("ROLE_ADMIN");
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $message = (new \Swift_Message('Inscription validée sur terraintennis'))
                    ->setFrom('send@example.com')
                    ->setTo('recipient@example.com')
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'emails/registration.html.twig',
                            [
                                'lastname' => $user->getLastname(),
                                'firstname' => $user->getFirstname()
                            ]
                        ),
                        'text/html'
                    )
                ;

                $mailer->send($message);
                return $this->redirectToRoute('app_login');
            }
        }
        else{
            $this->get('session')->getFlashBag()->add('error', 'Les mots de passes ne correspondent pas');
        }

        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword(): Response
    {

        return $this->render('security/forgotten_password.html.twig');
    }


}
