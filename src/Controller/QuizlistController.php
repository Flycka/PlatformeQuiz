<?php

namespace App\Controller;

use App\Entity\Copies;
use App\Entity\Quiz;
use App\Entity\Reponses;
use App\Entity\User;
use App\Form\ReponseType;
use App\Repository\CopiesRepository;
use App\Repository\QuizRepository;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
     * @Route("/user/copies", name="liste_copies_appre")
     */
    public function listeCopiesapp(CopiesRepository $copiesRepository, Security $security): Response
    {
        $user = $security->getUser();

        if ($user && in_array('ROLE_APPRE', $user->getRoles())) {
            $copies = $copiesRepository->findBy(['iduser' => $user]);
        } else {
            $copies = [];
        }

        return $this->render('Correction/liste_copies_user.html.twig', [
            'user' => $user,
            'copies' => $copies,
        ]);
    }





    /**
     * @Route("/copie/correction/{id}", name="app_copie_correction")
     */
    public function correctionQuiz(Copies $copie): Response
    {

        return $this->render("Correction/correction_quiz.html.twig", [
            "controller_name"=> "Correction de la copie",
            "questions" => $copie->getIdquiz()->getQuestions(),
            "reponses" => $copie->getReponses(),
            "copie" => $copie,
            "quiz" => $copie->getIdquiz(),
            "quizNoteMax" => $copie->getIdquiz()->getNoteMaximale(),
        ]);
    }

    #[Route('/copie/correction/flush/{id}', name: 'app_copie_correction_flush')]
    public function CopieCorrectionFlush(Copies $copie,EntityManagerInterface $manager, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $reponses=$copie->getReponses();
        // on récupère la donnée qui a la clé "annotation-copie" dans la requête et on l'enregistre dans annotation copie
        $annotationCopie = $request->get('annotation-copie');
        $copie->setAnnotationGlobale($annotationCopie);
        // on récupère la donnée qui a la clé "annotation-note" dans la requête et on la pour dans annotation copie
        $noteCopie = $request->get('note-copie');
        $copie->setNoteMaxQuiz($noteCopie);

        // on boucle sur la requête sous forme de tableau, avec $key= "le nom du champ de formulaire" et value= "sa valeur"
        foreach ($request->request->all() as $key=>$value){
            // si la clé est différente de "annotation-copie" et "note-copie"
            if($key !== "annotation-copie" && $key !== "note-copie"){

                // on décompose la chaine de caractère de la clé avec le "-"  comme élément séparateur
                $explode=explode( "-", $key );
                //on cherche la 1ère réponse qui à l'ID = à $explode[1]
                $reponse = $reponses->findFirst(function(int $index, Reponses $reponse) use($explode): bool {
                    return $reponse->getId() == $explode[1];
                });
                // on vérifie que $explode[0] = à "annotation" avant d'envoyer  la valeur dans la table annotation
                if ("annotation" == $explode[0]){
                    $reponse->setAnnotationQuestion($value);
                }
                // on vérifie que $explode[0] = à "note" avant d'envoyer  la valeur dans la table annotation
                elseif("note"== $explode[0]){
                    $reponse->setNoteReponse($value);
                }
            }
        }
        $manager->flush();
        return $this->redirectToRoute('liste_eleves');
    }

}

