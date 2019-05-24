<?php

namespace App\Controller\Dashboard\Staff;


use App\Entity\Departments;
use App\Entity\Designations;
use App\Entity\StaffDetails;
use App\Entity\StudentDetails;
use App\Entity\TeacherDetails;
use App\Entity\User;
use App\Form\AddTeacherType;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class StaffController
 * @package App\Controller\Dashboard\Teacher
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/staff", name="staff_")
 */
class StaffController extends AbstractController
{

    private $helperFunc;
    private $swiftMailer;

    public function __construct(HelperFunction $helperFunction, \Swift_Mailer $mailer)
    {
        $this->helperFunc = $helperFunction;
        $this->swiftMailer = $mailer;
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="update")
     */
    public function updateStaff(Request $request, $id)
    {
        $roles = [
            'ROLE_TEACHER'            => 'Teacher',
            'ROLE_ADMIN'              => 'Admin',
            'ROLE_COORDINATOR'        => 'Coordinator',
            'ROLE_COURSE_COORDINATOR' => 'Course Coordinator',
            'ROLE_EXAMINER'           => 'Examiner',
        ];
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        $designations = $this->getDoctrine()->getRepository(Designations::class)->findAll();
        $teacherDetails = $this->getDoctrine()->getRepository(StaffDetails::class)->findOneBy([
            'user' => $user
        ]);
        if($user == null){
            throw new NotFoundHttpException();
        }
        if ($request->isMethod('POST')){
            $department = $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department'));
            $designation = $this->getDoctrine()->getRepository(Designations::class)->find($request->request->get('designation'));
            $email = $request->request->get('email');
            $gender = $request->request->get('gender');
//            $role = array();
            $role = $request->request->get('roles');
//            dump($request->request->get('roles'));
//            die();
//            foreach ($request->request->get('roles') as $role){
//                $role[] = $role;
//            }
//            $role[] = $request->request->get($roles);
            $check_email = $this->getDoctrine()->getRepository(User::class)->findEmail($email, $id);
            if($check_email != null){
                $this->addFlash('danger', 'Email is already taken.');
                return $this->render('dashboard/staff/update_staff.html.twig', [
                    'departments' => $departments,
                    'designations' => $designations,
                    'user' => $user,
                    'roles' => $roles
                ]);
            }

            if($designation == $this->getDoctrine()->getRepository(Designations::class)->find(1)){
                $this->addFlash(
                    'danger',
                    'HOD already exists in the department.'
                );
                return $this->render('dashboard/staff/update_staff.html.twig', [
                    'departments' => $departments,
                    'designations' => $designations,
                    'user' => $user,
                    'roles' => $roles
                ]);
            }

            $user->setEmail($email);
            $user->setRoles($role);
            $user->setGender($gender);
            $user->setModifiedAt(new \DateTime());
            $teacherDetails->setDepartment($department);
            $teacherDetails->setDesignation($designation);
            $teacherDetails->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($teacherDetails);
            $em->flush();
            $this->addFlash(
                'success',
                'Staff is updated successfully.'
            );
            return $this->render('dashboard/staff/update_staff.html.twig', [
                'departments' => $departments,
                'designations' => $designations,
                'user' => $user,
                'roles' => $roles
            ]);
        }
        return $this->render('dashboard/staff/update_staff.html.twig', [
            'departments' => $departments,
            'designations' => $designations,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addStaff(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $teacherDetails = new StaffDetails();
        $avatar = "";

        $form = $this->createForm(AddTeacherType::class, null);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $teacherData = $form->getData();
            $password = $this->helperFunc->randomPasswordGenerator();
            $roles = $teacherData['roles'];
            $check = $this->getDoctrine()->getRepository(Designations::class)->find($teacherData['designation']);
            if($check == $this->getDoctrine()->getRepository(Designations::class)->find(1)){
                $this->addFlash(
                    'danger',
                    'HOD already exists in the department.'
                );
                return $this->render('dashboard/staff/add_staff.html.twig', [
                    'form' => $form->createView(),
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
                ]);
            }
            $email_exists = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $teacherData['email']]);
            if($email_exists){
                $this->addFlash(
                    'danger',
                    'Email is already taken try another one.'
                );
                return $this->render('dashboard/staff/add_staff.html.twig', [
                    'form' => $form->createView(),
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
                ]);
            }

            if($teacherData['gender'] === 'male'){
                $avatar = $this->helperFunc->getRandomBoyImage($this->getParameter('boys_avatar'));
            }

            if($teacherData['gender'] === 'female' or $teacherData['gender'] === 'other'){
                $avatar = $this->helperFunc->getRandomGirlImage($this->getParameter('girls_avatar'));
            }
            $fullname = $teacherData['fullname'];
            $data = array(
                'email' => $teacherData['email'],
                'password' => $password
            );
            if($this->sendWelcomeEmail($data)) {
                $em = $this->getDoctrine()->getManager();
                $user->setEmail($teacherData['email']);
                $user->setGender($teacherData['gender']);
                $user->setRoles($roles);
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $password)
                );
                $user->setCreatedAt(new \DateTime());
                $user->setModifiedAt(new \DateTime());
                $em->persist($user);
                $teacherDetails->setUser($user);
                $teacherDetails->setAvatar($avatar);
                $teacherDetails->setFullname($fullname);
                $teacherDetails->setDepartment($teacherData['department']);
                $teacherDetails->setDesignation($teacherData['designation']);
                $teacherDetails->setCreatedAt(new \DateTime());
                $teacherDetails->setModifiedAt(new \DateTime());
                $em->persist($teacherDetails);
                $em->flush();
                unset($postForm);
                $this->addFlash(
                    'success',
                    'Staff is added successfully'
                );
                $this->redirectToRoute('staff_add');

            }else{
                $this->addFlash(
                    'error',
                    'Something went wrong please try again.'
                );
                $this->redirectToRoute('staff_add');
            }

        }
        return $this->render('dashboard/staff/add_staff.html.twig', [
            'form' => $form->createView(),
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
        ]);
    }//end add teacher here


    /**
     * @Route("/all", name="all")
     */
    public function allStaff()
    {
        $staffs = $this->getDoctrine()->getRepository(User::class)->findStaff('ROLE_STUDENT');
        return $this->render('dashboard/staff/index.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'staffs'      => $staffs
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