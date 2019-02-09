<?php

namespace App\Controller\Dashboard\Staff;


use App\Entity\Departments;
use App\Entity\StaffDetails;
use App\Entity\TeacherDetails;
use App\Entity\User;
use App\Form\AddTeacherType;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/test")
     */
    public function test()
    {
        dump($this->getUser()->getStaffDetails()->getDesignation());
        die();
    }

    /**
     * @Route("/add", name="add")
     */
    public function addStaff(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $teacherDetails = new StaffDetails();
        $avatar = "";
        $roles = array("ROLE_TEACHER");

        $form = $this->createForm(AddTeacherType::class, null);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $teacherData = $form->getData();
            $password = $this->helperFunc->randomPasswordGenerator();
            $email_exists = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $teacherData['email']]);
            if($email_exists){
                $this->addFlash(
                    'danger',
                    'Email is already taken try another one.'
                );
                return $this->render('dashboard/staff/add_staff.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            if($teacherData['gender'] === 'male'){
                $avatar = $this->helperFunc->getRandomBoyImage($this->getParameter('boys_avatar'));
            }

            if($teacherData['gender'] === 'female' or $teacherData['gender'] === 'other'){
                $avatar = $this->helperFunc->getRandomGirlImage($this->getParameter('girls_avatar'));
            }
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