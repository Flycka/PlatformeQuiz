<?php

namespace App\Controller;

use App\Entity\Copies;
use App\Entity\Formation;
use App\Entity\Quiz;
use App\Entity\Reponses;
use App\Entity\Questions;
use App\Entity\User;
use App\Form\ReponseType;
use App\Repository\CopiesRepository;
use App\Repository\QuizRepository;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizlistController extends AbstractController
{
    private QuizRepository $quizRepository;
    private UserRepository $userRepository;
    public function __construct(QuizRepository $quizRepository, UserRepository $userRepository)
    {
        $this->quizRepository = $quizRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/quiz/list', name: 'quiz_list')]
    public function index(): Response
    {
        $quizzes = $this->quizRepository->findAll();
        $now = new \DateTime(); // crée une instance de la classe DateTime avec l'heure actuelle

        return $this->render('quiz/list.html.twig', [
            'quizzes' => $quizzes,
            'now' => $now,
        ]);
    }

    /**
     * @Route("/quiz/detail_quiz_app/{id}", name="quiz_vue_app")
     */
    public function quizdetail2(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
        $questions = $quiz->getQuestions();
        $user = $this->getUser();


        $reponsesExistantes = $entityManager->getRepository(Reponses::class)->findBy([
            'apprenant' => $user
        ]);

        if ($reponsesExistantes) {

            return $this->redirectToRoute('app_quiz_acceuilA');
        }

        $reponses = [];
        $i = 0;
        foreach ($questions as $question) {
            $reponse = new Reponses();
            $reponse->setIdQuestion($question);

            $reponses['reponses_' . $i] = $reponse;
            $i++;
        }

        $form = $this->createForm(ReponseType::class, ['reponses' => $reponses]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reponses = $form->getData()['reponses'];
            $user = $this->getUser();

            // Créer une nouvelle copie de l'utilisateur
            $copy = new Copies();
            $copy->setIduser($user);
            $copy->setIdquiz($quiz);
            $entityManager->persist($copy);

            foreach ($reponses as $name => $reponse) {
                $reponse->setCopie($copy);
                $reponse->setApprenant($user);
                $reponse->setReponse($form->get($name)->getData());
                $entityManager->persist($reponse);
            }

            $entityManager->flush();


            return $this->redirectToRoute('app_quiz_acceuilA');
        }

        return $this->render('quiz/detail_quiz_app.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/liste_eleves", name="liste_eleves")
     */
    public function listeEleves(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('Correction/liste_eleves.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/{id}/copies", name="liste_copies_user")
     */
    public function listeCopiesUser(User $user, CopiesRepository $copiesRepository): Response
    {
        $copies = $copiesRepository->findBy(['iduser' => $user]);
        return $this->render('Correction/liste_copies_user.html.twig', [
            'user' => $user,
            'copies' => $copies,
        ]);
    }


    /**
     * @Route("/quiz/{id}/copies", name="correction_quiz")
     */
    public function correctionQuiz (EntityManagerInterface $entityManager,Request $request,  Copies $id): Response
    {
        $copy = $entityManager->getRepository(Copies::class)->find($id);

        if (!$copy) {
            throw $this->createNotFoundException('Copy not found');
        }

        $quiz = $copy->getIdquiz();
        $questions = $quiz->getQuestions();
        $reponses = $copy->getReponses();

        return $this->render('Correction/correction_quiz.html.twig', [
            'copy' => $copy,
            'quiz' => $quiz,
            'questions' => $questions,
            'reponses' => $reponses,
        ]);
    }



}

