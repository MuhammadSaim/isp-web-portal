<?php

namespace App\Controller\Dashboard;

use App\Entity\Departments;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
