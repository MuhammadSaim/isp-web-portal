<?php


namespace App\Controller\Dashboard\Attendence;


use App\Entity\Departments;
use App\Entity\TeacherCourseMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AttendenceController
 * @package App\Controller\Dashboard\Attendence
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT', 'ROLE_COORDINATOR'])", statusCode=404)
 * @Route("/attendence", name="attendence_")
 */
class AttendenceController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/all", name="all")
     */
    public function allCourses()
    {
        if($this->isGranted('ROLE_TEACHER')){
            $courses = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->findBy([
                'teacher' => $this->getUser()
            ]);

            return $this->render("dashboard/attendence/all_courses.html.twig", [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'courses'     => $courses
            ]);

        }
    }
}