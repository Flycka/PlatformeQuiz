<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route('/', name: 'app_login')]
      public function index(AuthenticationUtils $authenticationUtils): Response
      {
                   // get the login error if there is one
                   $error = $authenticationUtils->getLastAuthenticationError();

                   // last username entered by the user
                  $lastUsername = $authenticationUtils->getLastUsername();

          if ($this->isGranted('ROLE_FORMA')) {
              return $this->redirectToRoute('app_quiz_acceuilF');
          }
          if ($this->isGranted('ROLE_APPRE')) {
              return $this->redirectToRoute('app_quiz_acceuilA');
          }

          return $this->render('login/index.html.twig', [
                           'controller_name' => 'LoginController',
                           'last_username' => $lastUsername,
                           'error'         => $error,
          ]);
      }

  }