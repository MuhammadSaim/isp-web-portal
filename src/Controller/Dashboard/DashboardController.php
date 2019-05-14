<?php

namespace App\Controller\Dashboard;

use App\Entity\Assignments;
use App\Entity\Departments;
use App\Entity\StaffDetails;
use App\Entity\StudentDetails;
use App\Entity\User;
use Intervention\Image\ImageManagerStatic as Image;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/dashboard", name="dashboard_")
 */
class DashboardController extends AbstractController
{

    private $hellperFunction;
    private $passwordEncoder;

    public function __construct(HelperFunction $helperFunction, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->hellperFunction = $helperFunction;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->redirectToRoute('department_teacher', ['slug' => 'computer-sciences']);
//        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
//        if($this->isGranted('ROLE_STUDENT')){
//            $assignments = $this->getDoctrine()->getRepository(Assignments::class)->getTopAssignmentsOfStudents($this->getUser()->getStudentDetails());
//            return $this->render('dashboard/student_dashboard_index.html.twig', [
//                'departments' => $departments,
//                'assignments' => $assignments
//            ]);
//        }
//        if($this->isGranted('ROLE_TEACHER')){
//            return $this->render('dashboard/student_dashboard_index.html.twig', [
//                'departments' => $departments
//            ]);
//        }

    }


    /**
     * @Route("/edit", name="edit_profile")
     */
    public function editProfile(Request $request)
    {
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        if($request->isMethod('POST')){
            if(!$this->isGranted('ROLE_STUDENT')){
                $details = $this->getDoctrine()->getRepository(StaffDetails::class)->findOneBy([
                    'user' => $this->getUser()
                ]);
                if($request->files->get('profile_pic') != null){
                    $fileAllow = ['jpg', 'jpeg', 'png', 'gif'];
                    $file = $request->files->get('profile_pic');
                    $ext = $file->guessExtension();
                    $picname = $this->hellperFunction->getUniqueFileName().'.'.$ext;
                    if(!in_array($ext, $fileAllow)){
                        $this->addFlash('danger', $ext.' is not allowed to upload');
                        return $this->render('dashboard/staff/edit_profile.html.twig', [
                            'departments' => $departments,
                            'details'  => $details
                        ]);
                    }
                    try{
                        $thumbnail = Image::make($file->getRealPath());
                        $thumbnail->resize(500, 500);
                        $thumbnail->save($this->getParameter('profile_avatar').$picname);
//                        $file->move(
//                            $this->getParameter('profile_avatar'),
//                            $picname
//                        );
                        $details->setAvatar($picname);
                    }catch (FileException $e){
                        $this->addFlash('danger', 'Something went wrong please try again');
                        return $this->render('dashboard/staff/edit_profile.html.twig', [
                            'departments' => $departments,
                            'details'  => $details
                        ]);
                    }
                }
                $fullname = $request->request->get('fullname');
                $phoneNumber = $request->request->get('phone');
                $facebook = $request->request->get('facebook');
                $twitter = $request->request->get('twitter');
                $google = $request->request->get('google');
                $site = $request->request->get('website');
                if(!empty($fullname)){
                    $details->setFullname($fullname);
                }
                if(!empty($phoneNumber)){
                    $details->setPhoneNumber($phoneNumber);
                }
                if(!empty($facebook)){
                    $details->setFacebookProfile($facebook);
                }
                if(!empty($twitter)){
                    $details->setTwitterProfile($twitter);
                }
                if(!empty($google)){
                    $details->setGoogleProfile($google);
                }
                if(!empty($site)){
                    $details->setSiteUrl($site);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($details);
                $em->flush();
                $this->addFlash('success', 'profile Updated is successfully.');
                return $this->render('dashboard/staff/edit_profile.html.twig', [
                    'departments' => $departments,
                    'details'     => $details
                ]);
            }else{
                $details = $this->getDoctrine()->getRepository(StudentDetails::class)->findOneBy([
                    'user' => $this->getUser()
                ]);
                if($request->files->get('profile_pic') != null){
                    $fileAllow = ['jpg', 'jpeg', 'png', 'gif'];
                    $file = $request->files->get('profile_pic');
                    $ext = $file->guessExtension();
                    $picname = $this->hellperFunction->getUniqueFileName().'.'.$ext;
                    if(!in_array($ext, $fileAllow)){
                        $this->addFlash('danger', $ext.' is not allowed to upload');
                        return $this->render('dashboard/staff/edit_profile.html.twig', [
                            'departments' => $departments,
                            'details'  => $details
                        ]);
                    }
                    try{
                        $thumbnail = Image::make($file->getRealPath());
                        $thumbnail->resize(500, 500);
                        $thumbnail->save($this->getParameter('profile_avatar').$picname);
//                        $file->move(
//                            $this->getParameter('profile_avatar'),
//                            $picname
//                        );
                        $details->setAvatar($picname);
                    }catch (FileException $e){
                        $this->addFlash('danger', 'Something went wrong please try again');
                        return $this->render('dashboard/staff/edit_profile.html.twig', [
                            'departments' => $departments,
                            'details'  => $details
                        ]);
                    }
                }
                $fullname = $request->request->get('fullname');
                $phoneNumber = $request->request->get('phone');
                $facebook = $request->request->get('facebook');
                $twitter = $request->request->get('twitter');
                $google = $request->request->get('google');
                $site = $request->request->get('website');
                if(!empty($fullname)){
                    $details->setFullname($fullname);
                }
                if(!empty($phoneNumber)){
                    $details->setPhoneNumber($phoneNumber);
                }
                if(!empty($facebook)){
                    $details->setFacebookProfile($facebook);
                }
                if(!empty($twitter)){
                    $details->setTwitterProfile($twitter);
                }
                if(!empty($google)){
                    $details->setGoogleProfile($google);
                }
                if(!empty($site)){
                    $details->setSiteUrl($site);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($details);
                $em->flush();
                $this->addFlash('success', 'profile Updated is successfully.');
                return $this->render('dashboard/student/edit_profile.html.twig', [
                    'departments' => $departments,
                    'details'     => $details
                ]);
            }
        }
        if($this->isGranted(['ROLE_TEACHER', 'ROLE_EXAMINER', 'ROLE_COURSE_COORDINATOR', 'ROLE_COORDINATOR', 'ROLE_ADMIN'])){
            $details = $this->getDoctrine()->getRepository(StaffDetails::class)->findOneBy([
                'user' => $this->getUser()
            ]);
            return $this->render('dashboard/staff/edit_profile.html.twig', [
                'departments' => $departments,
                'details'     => $details
            ]);
        }else{
            $details = $this->getDoctrine()->getRepository(StudentDetails::class)->findOneBy([
                'user' => $this->getUser()
            ]);
            return $this->render('dashboard/student/edit_profile.html.twig', [
                'departments' => $departments,
                'details'     => $details
            ]);
        }

    }


    /**
     * @param Request $request
     * @Route("/change/password", name="change_password")
     */
    public function changePassword(Request $request)
    {
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        if($request->isMethod('POST')){
            $password = $request->request->get('password');
            $new_password = $request->request->get('new_password');
            $confirm_password = $request->request->get('confirm_password');
            $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
            if($this->passwordEncoder->isPasswordValid($user, $password)){
                if($new_password === $confirm_password){
                    $enc_pass = $this->passwordEncoder->encodePassword($user, $new_password);
                    $user->setPassword($enc_pass);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Password is updated successfully.');
                    return $this->render('security/new_password.html.twig', [
                        'departments' => $departments
                    ]);
                }else{
                    $this->addFlash('danger', 'Please make sure both passwords are the same.');
                    return $this->render('security/new_password.html.twig', [
                        'departments' => $departments
                    ]);
                }
            }else{
                $this->addFlash('danger', 'your password is incorrect.');
                return $this->render('security/new_password.html.twig', [
                    'departments' => $departments
                ]);
            }
        }
        return $this->render('security/new_password.html.twig', [
            'departments' => $departments
        ]);
    }
}
