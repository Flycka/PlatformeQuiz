<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilFormaRoute extends AbstractController
{
    #[Route('/quiz/acceuilF', name: 'app_quiz_acceuilF')]
    public function redirectionformaF(): Response
    {
        return $this->render('/QuizAcceuil/AcceuilFormateur.html.twig');
    }
    #[Route('/quiz/acceuilA', name: 'app_quiz_acceuilA')]
    public function redirectionformaA(): Response
    {
        return $this->render('/QuizAcceuil/AcceuilApprenant.html.twig');
    }
}