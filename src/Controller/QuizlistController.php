<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Quiz;
use App\Entity\Reponses;
use App\Entity\Questions;
use App\Entity\User;
use App\Form\ReponseType;
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

            foreach ($reponses as $name => $reponse) {
                $reponse->setApprenant($user);
                $reponse->setReponse($form->get($name)->getData());
                $reponse->setQuizId($quiz);
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

    #[Route('/quiz/liste_élève', name: 'Liste_élève')]
    public function listeEleve(EntityManagerInterface $entityManager): Response
    {

        $formations = $entityManager->getRepository(Formation::class)->findAll();
        $users = $this->userRepository->findAllUser('["ROLE_APPRE"]');

        return $this->render('Correction/liste_élève.html.twig', [
            'formations'=> $formations,
            'users'=>$users
        ]);
    }

}

