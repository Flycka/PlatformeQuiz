<?php

namespace App\Controller;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreaQuestType extends AbstractController
{
    /**
     * @Route("/quiz/creerquestion/{id}", name="crea_quest")
     */
    public function creerQuestion(Request $request, EntityManagerInterface $entityManager, Quiz $quiz): Response
    {
        $question = new Questions();
        $form = $this->createForm(QuestionType::class, $question);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Enregistrer la question dans la base de données
            $question->setQuiz($quiz);
            $entityManager->persist($question);

            // Enregistrer et rediriger l'utilisateur en fonction du bouton cliqué
            if ($request->request->has('action') && $request->request->get('action') == 'save_and_continue') {
                $entityManager->flush();
                return $this->redirectToRoute('crea_quest', ['id' => $quiz->getId()]);
            } else {
                $entityManager->flush();
                return $this->redirectToRoute('quiz_list');
            }
        }

        // Afficher le formulaire
        return $this->render('/quiz/question.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


