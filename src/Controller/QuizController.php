<?php

namespace App\Controller;

use App\Entity\Reponses;
use App\Entity\User;

use App\Repository\QuizRepository;
use App\Repository\ReponsesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Quiz;
use App\Form\CreaQuizType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{

    private QuizRepository $quizRepository;
    private ReponsesRepository $reponsesRepository;

    public function __construct(QuizRepository $quizRepository, ReponsesRepository $reponsesRepository)
    {
        $this->quizRepository = $quizRepository;
        $this->reponsesRepository = $reponsesRepository;

    }
    /**
     * @Route("/quiz/creer", name="crea_quiz")
     */
    public function creaQuiz(EntityManagerInterface $entityManager, Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(CreaQuizType::class, $quiz);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // sauvegarder le quiz en base de donnée
            $user = $this->getUser();
            $quiz->setFormateur($user);
            $entityManager->persist($quiz);
            $entityManager->flush();

            // récupérer l'ID du quiz nouvellement créé
            $id = $quiz->getId();

            // rediriger l'utilisateur vers la page création de question avec l'ID du quiz
            return $this->redirectToRoute('crea_quest', ['id' => $id]);
        }

        return $this->render('quiz/creer.html.twig', [
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/quiz/{id}/modifier", name="modif_quiz")
     */
    public function modifQuiz(Request $request, EntityManagerInterface $entityManager, Quiz $quiz): Response
    {
        $form = $this->createForm(CreaQuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('quiz_confirmation');
        }

        return $this->render('quiz/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quiz/supprimer/{id}", name="supprimer_quiz")
     */
    public function supprimerQuiz(EntityManagerInterface $entityManager, $id): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);

        // Vérifier que l'utilisateur connecté est le formateur du quiz
        if ($this->getUser() !== $quiz->getFormateur()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($quiz);
        $entityManager->flush();

        // Rediriger l'utilisateur vers la page d'accueil des quiz
        return $this->redirectToRoute('quiz_list');
    }

    /**
     * @Route("/quiz/confirmation", name="quiz_confirmation")
     */
    public function confirmation(): Response
    {
        return $this->render('quiz/AcceuilFormateur.html.twig');
    }

    /**
     * @Route("/user/{id}/quizzes", name="liste_quiz_user")
     */
    public function listeQuizUser(EntityManagerInterface $entityManager,User $user) : Response
    {
        $quizzes = $this->quizRepository->findAll();
        $reponses = $this->reponsesRepository->findAll();
        $quizIds = array(); // Initialiser la liste des ids de quiz déjà affichés
        return $this->render('Correction/liste_quiz_user.html.twig', [
            'user' => $user,
            'quizzes' => $quizzes,
            'reponses' => $reponses,
            'quizIds' => $quizIds // Passer la liste à la vue
        ]);
    }

}