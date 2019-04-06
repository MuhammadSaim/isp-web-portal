<?php

namespace App\Controller\Dashboard\Quize;


use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Quizzes;
use App\Entity\QuizzesEvaluation;
use App\Entity\Sections;
use App\Entity\Semesters;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Entity\User;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuizController
 * @package App\Controller\Dashboard\Quize
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
 * @Route("/quiz", name="quiz_")
 */
class QuizController extends AbstractController
{

    private $helperFunction;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunction = $helperFunction;
    }

    /**
     * @param Request $request
     * @Route("/new", name="new")
     */
    public function addQuiz(Request $request)
    {
        $quiz = new Quizzes();
        $teacherRepo = $this->getDoctrine()->getRepository(TeacherCourseMapping::class);
        $courses = $teacherRepo->getDistinctCourses($this->getUser());
        $program = $teacherRepo->getDistinctProgram($this->getUser());
        $department = $teacherRepo->getDistinctDepartment($this->getUser());
        $semester = $teacherRepo->getDistinctSemester($this->getUser());
        $section = $teacherRepo->getDistinctSection($this->getUser());
        $data = [
            'courses'    => $courses,
            'programs'    => $program,
            'departments' => $department,
            'semesters'   => $semester,
            'sections'    => $section
        ];
        if($request->isMethod('POST')){
            $department = $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department'));
            $program = $this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program'));
            $course = $this->getDoctrine()->getRepository(Courses::class)->find($request->request->get('course'));
            $semester = $this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester'));
            $section = $this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section'));
            $quizTitle = $request->request->get('quiz_title');
            $quizDate = $request->request->get('quiz_date');
            $quizDescription = $request->request->get('description');
            $quizTotalMarks = $request->request->get('total_marks');
            $slug = $this->helperFunction->slugify($quizTitle);
            $checkSlug = $this->getDoctrine()->getRepository(Quizzes::class)->findBy([
                'quizSlug' => $slug
            ]);
            if($checkSlug != null){
                $slug = $slug.'-'.$this->helperFunction->getUiqueName();
            }
            $quiz->setTeacher($this->getUser());
            $quiz->setQuizTitle($quizTitle);
            $quiz->setTotalMarks($quizTotalMarks);
            $quiz->setQuizDate(new \DateTime($quizDate));
            $quiz->setQuizSlug($slug);
            $quiz->setQuizDescription($quizDescription);
            $quiz->setDepartment($department);
            $quiz->setProgram($program);
            $quiz->setCourse($course);
            $quiz->setSemester($semester);
            $quiz->setSection($section);
            $quiz->setCreatedAt(new \DateTime());
            $quiz->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();
            $this->addFlash('success', 'Quiz is created successfully!.');
            return $this->render('dashboard/Quize/new_quiz.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'data' => $data
            ]);

        }
        return $this->render('dashboard/Quize/new_quiz.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'data' => $data
        ]);

    }

    /**
     * @Route("/all", name="all")
     */
    public function allQuizzes()
    {
        if($this->isGranted('ROLE_TEACHER')){
            $quizzes = $this->getDoctrine()->getRepository(Quizzes::class)->findBy([
                'teacher' => $this->getUser()
            ]);
        }
        return $this->render('dashboard/Quize/all_quizzess.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'quizzes' => $quizzes
        ]);
    }

    /**
     * @param $quizSlug
     * @Route("/show/{quizSlug}", name="show")
     */
    public function showQuiz($quizSlug)
    {
        $quiz = $this->getDoctrine()->getRepository(Quizzes::class)->findBy([
            'quizSlug' => $quizSlug
        ]);
        if($quiz == null){
            throw new NotFoundHttpException();
        }
        return $this->render('dashboard/Quize/show_quiz.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'quiz' => $quiz[0]
        ]);
    }

    /**
     * @param $quizSlug
     * @Route("/delete/{quizSlug}", name="delete")
     */
    public function deleteQuiz($quizSlug)
    {
        $quiz = $this->getDoctrine()->getRepository(Quizzes::class)->findBy([
            'quizSlug' => $quizSlug
        ]);
        if($quiz == null){
            throw new NotFoundHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($quiz[0]);
        $em->flush();
        return $this->redirectToRoute('quiz_all');
    }

    /**
     * @param $quizSlug
     * @Route("/update/{quizSlug}", name="update")
     */
    public function updateQuiz($quizSlug)
    {
        dump($quizSlug);
        die();
    }

    /**
     * @Route("/evaluate/save/{quiz}", name="evaluate_save")
     */
    public function saveQuizEvaluation(Request $request, $quiz)
    {
        $quiz = $this->getDoctrine()->getRepository(Quizzes::class)->findBy([
            'quizSlug' => $quiz
        ]);
        if ($quiz == null) {
            throw new NotFoundHttpException();
        }
//        dump(count($quiz[0]->getQuizeEvaluation()));
//        die();
        if ($request->isMethod('POST')) {
            $data = $request->request->get('quizForm');
            $quizzesResults = array_chunk($data, 2);
            if (count($quiz[0]->getQuizeEvaluation()) == 0) {
//                $quizEv = new QuizzesEvaluation();
                $data = array();
                foreach ($quizzesResults as $result) {
                    $quizEv = new QuizzesEvaluation();
                    $quizEv->setQuiz($quiz[0]);
                    $quizEv->setObtainedMarks($result[0]);
                    $quizEv->setUser($this->getDoctrine()->getRepository(User::class)->find($result[1]));
                    $quizEv->setCreatedAt(new \DateTime());
                    $quizEv->setModifiedAt(new \DateTime());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($quizEv);
                    $em->flush();
                }
                $this->addFlash('success', 'Quiz is evaluated successfully.');
                return $this->redirectToRoute('quiz_evaluate', [
                    'quiz' => $quiz[0]->getQuizSlug()
                ]);
            } else {

//                dump($quizEv);
//                die();
                foreach ($quizzesResults as $result) {
                    $quizEv = $this->getDoctrine()->getRepository(QuizzesEvaluation::class)->findBy([
                        'quiz' => $quiz[0],
                        'user' => $this->getDoctrine()->getRepository(User::class)->find($result[1])
                    ]);
                    $quizEv[0]->setObtainedMarks($result[0]);
                    $quizEv[0]->setModifiedAt(new \DateTime());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($quizEv[0]);
                    $em->flush();
//                }
                }
                $this->addFlash('success', 'Quiz is evaluated successfully.');
                return $this->redirectToRoute('quiz_evaluate', [
                    'quiz' => $quiz[0]->getQuizSlug()
                ]);
            }
        }
    }

    /**
     * @Route("/evaluate/{quiz}", name="evaluate")
     */
    public function quizEvaluated(Request $request, $quiz)
    {
        $quiz = $this->getDoctrine()->getRepository(Quizzes::class)->findBy([
            'quizSlug' => $quiz
        ]);
        if($quiz == null){
            throw new NotFoundHttpException();
        }
//        dump($quiz[0]->getDepartment());
//        die();
        if(count($quiz[0]->getQuizeEvaluation()) == 0){
            $students = $this->getDoctrine()->getRepository(StudentDetails::class)->findBy([
                'department' => $quiz[0]->getDepartment(),
                'program'    => $quiz[0]->getProgram(),
                'semester'   => $quiz[0]->getSemester(),
                'section'    => $quiz[0]->getSection()
            ]);
            return $this->render('dashboard/Quize/evaluate_quiz.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'students'    => $students,
                'quiz'    => $quiz[0],
                'old' => false
            ]);
        }else{
            $students = $this->getDoctrine()->getRepository(QuizzesEvaluation::class)->findBy([
                'quiz' => $quiz[0]
            ]);
            return $this->render('dashboard/Quize/evaluate_quiz.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'students'    => $students,
                'quiz'    => $quiz[0],
                'old' => true
            ]);
        }

    }
}