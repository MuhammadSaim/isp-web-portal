<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 2/8/19
 * Time: 11:18 PM
 */

namespace App\Controller\Dashboard\Department;


use App\Entity\Departments;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentController
 * @package App\Controller\Dashboard\Department
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/department", name="department_")
 */
class DepartmentController extends AbstractController
{


    /**
     * @Route("/staff/{slug}", name="teacher")
     */
    public function department_staff_details($slug)
    {
        $department_details = $this->getDoctrine()->getRepository(Departments::class)->findOneBy(['slug' => $slug]);
        dump($department_details);
//        die();
        if($department_details == null){
            throw $this->createNotFoundException();
        }
        return $this->render(
            "dashboard/department/department_staff_details.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'departmentDetails' => $department_details,
            'active_class' => 'teacher'
        ]);
    }

    /**
     * @Route("/students/{slug}", name="student")
     */
    public function department_student_details($slug)
    {
        $department_details = $this->getDoctrine()->getRepository(Departments::class)->findOneBy(['slug' => $slug]);
        dump($department_details);
        if($department_details == null){
            throw $this->createNotFoundException();
        }
        return $this->render(
            "dashboard/department/department_student_details.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'departmentDetails' => $department_details,
            'active_class' => 'student'
        ]);
    }

    /**
     * @Route("/courses/{slug}", name="courses")
     */
    public function department_courses_details($slug)
    {
        $department_details = $this->getDoctrine()->getRepository(Departments::class)->findOneBy(['slug' => $slug]);
        dump($department_details);
        if($department_details == null){
            throw $this->createNotFoundException();
        }
        return $this->render(
            "dashboard/department/department_courses_details.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'departmentDetails' => $department_details,
            'active_class' => 'courses'
        ]);
    }

}