<?php

namespace App\Controller\Dashboard\Student;

use App\Entity\Departments;
use App\Entity\StudentDetails;
use App\Entity\User;
use App\Form\AddStudentType;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class StudentController
 * @package App\Controller\Dashboard\Student
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/student", name="student_")
 */
class StudentController extends AbstractController
{

    private $helperFunc;
    private $swiftMailer;

    public function __construct(HelperFunction $helperFunction, \Swift_Mailer $mailer)
    {
        $this->helperFunc = $helperFunction;
        $this->swiftMailer = $mailer;
    }

    /**
     * @Route("/", name="all")
     */
    public function allStudents()
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }


    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
//        $data = $this->getDoctrine()->getRepository(StudentDetails::class)->createQueryBuilder('sr')
//            ->leftJoin('App\Entity\User', 'u', 'sr.userId = u.id')
//            ->getQuery()
//            ->getResult();
//
//
//        dump($data);
//        die();


        dump($this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $this->getUser()]));
        die();

    }


    /**
     * @Route("/add", name="add")
     *
     */
    public function addStudent(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = new User();
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        $roles = array('ROLE_STUDENT');
        $avatar = "";
        $studentDetails = new StudentDetails();
        $form = $this->createForm(AddStudentType::class, []);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $studentData = $form->getData();
            $password = $this->helperFunc->randomPasswordGenerator();

            $email_exists = $this->getDoctrine()
                                ->getRepository(User::class)
                                ->findOneBy(['email' => $studentData['email']]);
            $regno_exists = $this->getDoctrine()
                                 ->getRepository(StudentDetails::class)
                                 ->findOneBy(['regNo' => $studentData['regno']]);

            if($email_exists && $regno_exists){
                $this->addFlash(
                    'danger',
                    'Email and RegNo is already taken.'
                );
            }else{
                if($email_exists){
                    $this->addFlash(
                        'danger',
                        'Email is already taken try another one.'
                    );
                }

                if($regno_exists){
                    $this->addFlash(
                        'danger',
                        'RegNo is already taken try another one.'
                    );
                }
            }


            if($email_exists or $regno_exists){
                return $this->render('dashboard/student/add_student.html.twig', [
                    'form' => $form->createView(),
                    'departments' => $departments
                ]);
            }


            if($studentData['is_CR_GR'] === 'yes'){
                if($studentData['gender'] === 'male'){
                    array_push($roles, 'ROLE_CR');
                }
                if($studentData['gender'] === 'female'){
                    array_push($roles, 'ROLE_GR');
                }
                if($studentData['gender'] === 'other'){
                    array_push($roles, 'ROLE_GR');
                }
            }

            if($studentData['gender'] === 'male'){
                $avatar = $this->helperFunc->getRandomBoyImage($this->getParameter('boys_avatar'));
            }

            if($studentData['gender'] === 'female' or $studentData['gender'] === 'other'){
                $avatar = $this->helperFunc->getRandomGirlImage($this->getParameter('girls_avatar'));
            }

            $data = array(
                'email' => $studentData['email'],
                'password' => $password
            );
            if($this->sendWelcomeEmail($data)){
                $user->setEmail($studentData['email']);
                $user->setPassword(
                    $userPasswordEncoder->encodePassword($user, $password)
                );
                $user->setGender($studentData['gender']);
                $user->setRoles($roles);
                $user->setCreatedAt(new \DateTime());
                $user->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $studentDetails->setUser($user);
                $studentDetails->setDepartment($studentData['departments']);
                $studentDetails->setProgram($studentData['courses']);
                $studentDetails->setAvatar($avatar);
                $studentDetails->setRegNo(strtoupper($studentData['regno']));
                $studentDetails->setCreatedAt(new \DateTime());
                $studentDetails->setModifiedAt(new \DateTime());
                $em->persist($studentDetails);
                $em->flush();
                unset($postForm);
                $this->addFlash(
                    'success',
                    'Student is added successfully'
                );
                $this->redirectToRoute('student_add');
            }else{
                $this->addFlash(
                    'error',
                    'Something went wrong please try again.'
                );
                $this->redirectToRoute('student_add');
            }


        }
        return $this->render('dashboard/student/add_student.html.twig', [
            'form' => $form->createView(),
            'departments' => $departments
        ]);
    }


    public function sendWelcomeEmail($data){
        $message = (new \Swift_Message('Welcome To ISP Portal'))
            ->setFrom('noreply@portal.isp.edu.pk')
            ->setTo($data['email'])
            ->setBody(
                $this->renderView(
                    'emails/welcome.html.twig',
                    $data
                ), 'text/html'
            );

        if($this->swiftMailer->send($message))
            return true;
        else
            return false;

    }

}
