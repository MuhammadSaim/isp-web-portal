<?php

namespace App\Controller\Dashboard\Student;

use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\Semesters;
use App\Entity\StudentDetails;
use App\Entity\User;
use App\Form\AddStudentType;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @Route("/all", name="all")
     */
    public function allStudents()
    {
        $students = $this->getDoctrine()->getRepository(User::class)->findByRole('ROLE_STUDENT');
        return $this->render('dashboard/student/index.html.twig', [
            'departments'  => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'students' => $students,
        ]);
    }


    /**
     * @param Request $request
     * @Route("/update/{id}", name="update")
     */
    public function updateStudent(Request $request, $id)
    {
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $studentDetails = $this->getDoctrine()->getRepository(StudentDetails::class)->findBy([
            'user' => $user
        ]);
        if($user == null){
            throw new NotFoundHttpException();
        }
        if($request->isMethod('POST')){
            $roles = array('ROLE_STUDENT');
            $department = $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department'));
            $program = $this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program'));
            $semester = $this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester'));
            $section = $this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section'));
//            dump([
//                $department,
//                $program,
//                $semester,
//                $section
//            ]);
//            die();
            $cr_gr = $request->request->get('cr_gr');
            $email = $request->request->get('email');
            $gender = $request->request->get('gender');
            $reg = strtoupper($request->request->get('regno'));

            $check_email = $this->getDoctrine()->getRepository(User::class)->findEmail($email, $id);
            $check_reg = $this->getDoctrine()->getRepository(User::class)->findEmail($reg, $id);
            if($check_email == null && $check_reg == null){
                if($cr_gr === 'yes'){
                    if($gender === 'male'){
                        array_push($roles, 'ROLE_CR');
                    }
                    if($gender === 'female'){
                        array_push($roles, 'ROLE_GR');
                    }
                    if($gender === 'other'){
                        array_push($roles, 'ROLE_GR');
                    }
                }
                $user->setEmail($email);
                $user->setRoles($roles);
                $user->setModifiedAt(new \DateTime());
                $studentDetails[0]->setDepartment($department);
                $studentDetails[0]->setProgram($program);
                $studentDetails[0]->setRegNo(strtoupper($reg));
                $studentDetails[0]->setSemester($semester);
                $studentDetails[0]->setSection($section);
                $studentDetails[0]->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->persist($studentDetails[0]);
                $em->flush();
                $this->addFlash('success', 'Student is updated successfully');
                return $this->render('dashboard/student/update_student.html.twig', [
                    'user' => $user,
                    'departments' => $departments,
                    'programs'    => $this->getDoctrine()->getRepository(Programs::class)->findAll(),
                    'semesters'    => $this->getDoctrine()->getRepository(Semesters::class)->findAll(),
                    'sections'    => $this->getDoctrine()->getRepository(Sections::class)->findAll(),
                ]);
            }

            if($check_email != null){
                $this->addFlash(
                    'danger',
                    'Email is already taken try another one.'
                );
            }
            if($check_reg != null) {
                $this->addFlash(
                    'danger',
                    'RegNo is already taken try another one.'
                );
            }

            if($check_reg != null or $check_email != null){
                return $this->render('dashboard/student/add_student.html.twig', [
                    'user' => $user,
                    'departments' => $departments,
                    'programs'    => $this->getDoctrine()->getRepository(Programs::class)->findAll(),
                    'semesters'    => $this->getDoctrine()->getRepository(Semesters::class)->findAll(),
                    'sections'    => $this->getDoctrine()->getRepository(Sections::class)->findAll(),
                ]);
            }

        }
        return $this->render('dashboard/student/update_student.html.twig', [
            'user' => $user,
            'departments' => $departments,
            'programs'    => $this->getDoctrine()->getRepository(Programs::class)->findAll(),
            'semesters'    => $this->getDoctrine()->getRepository(Semesters::class)->findAll(),
            'sections'    => $this->getDoctrine()->getRepository(Sections::class)->findAll(),
        ]);


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
            $fullname = $studentData['fullname'];
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
                $studentDetails->setProgram($studentData['programs']);
                $studentDetails->setSection($studentData['sections']);
                $studentDetails->setSemester($studentData['semesters']);
                $studentDetails->setAvatar($avatar);
                $studentDetails->setFullname($fullname);
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
