<?php

namespace App\Controller\Dashboard;

use App\Entity\Departments;
use App\Entity\StaffDetails;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/dashboard", name="dashboard_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
//        dump($this->getUser()->getStudentDetails()->getSemester());
//        $courses = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->getDistinctProgram($this->getUser()->getId());
//        dump($this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getDepartment());
//        die();
        return $this->render('dashboard/index.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
        ]);
    }


    /**
     * @param Request $request
     * @Route("/edit", name="edit_profile")
     */
    public function editProfile(Request $request)
    {
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        if($request->isMethod('POST')){
            if($this->isGranted(['ROLE_TEACHER', 'ROLE_EXAMINER', 'ROLE_COURSE_COORDINATOR', 'ROLE_COORDINATOR', 'ROLE_ADMIN'])){
                $details = $this->getDoctrine()->getRepository(StaffDetails::class)->findOneBy([
                    'user' => $this->getUser()
                ]);
                $fullname = $request->request->get('fullname');
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
}
