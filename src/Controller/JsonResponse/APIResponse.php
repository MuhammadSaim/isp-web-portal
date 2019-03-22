<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 2/23/19
 * Time: 11:56 AM
 */

namespace App\Controller\JsonResponse;



use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Sections;
use App\Entity\SemesterCourseMapping;
use App\Entity\Semesters;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIResponse
 * @package App\Controller\JsonResponse
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/api", name="api_")
 */
class APIResponse extends AbstractController
{

    /**
     * @Route("/departments", name="all_departments")
     */
    public function getAllDepartmenst()
    {
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        $departmentsJson = array();
        foreach ($departments as $department){
            $departmentsJson[] = array(
                'id' => $department->getId(),
                'slug' => $department->getSlug(),
                'department' => $department->getDepartment()
            );
        }

        return new JsonResponse($departmentsJson);
    }

    /**
     * @return JsonResponse
     * @Route("/semesters", name="all_semesters")
     */
    public function getAllSemester(){
        $semesters = $this->getDoctrine()->getRepository(Semesters::class)->findAll();
        $semesterJson = array();
        foreach ($semesters as $semester){
            $semesterJson[] = array(
                'id' => $semester->getId(),
                'semester' => $semester->getSemester()
            );
        }

        return new JsonResponse($semesterJson);
    }


    /**
     * @Route("/sections", name="all_sections")
     */
    public function getAllSections()
    {
        $sections = $this->getDoctrine()->getRepository(Sections::class)->findAll();
        $sectionJson = array();
        foreach ($sections as $section){
            $sectionJson[] = array(
                'id' => $section->getId(),
                'section' => $section->getSection()
            );
        }

        return new JsonResponse($sectionJson);
    }


    /**
     * @Route("/teachers", name="all_teachers")
     */
    public function getAllTeachers()
    {
        $teachers = $this->getDoctrine()->getRepository(User::class)->findByRole('ROLE_TEACHER');
        $teachersJson = array();
        foreach ($teachers as $teacher){
            $teachersJson[] = array(
                'id' => $teacher->getId(),
                'email' => $teacher->getEmail(),
                'fullname' => $teacher->getStaffDetails()->getFullname()
            );
        }
        return new JsonResponse($teachersJson);
    }

    /**
     * @Route("/courses", name="all_courses")
     */
    public function getCourses(Request $request)
    {
        $department = $request->query->get('department');
        $program = $request->query->get('program');
        $semester = $request->query->get('semester');


        $repo = $this->getDoctrine()->getRepository(SemesterCourseMapping::class);
        $courses = $repo->createQueryBuilder('s')
            ->andWhere('s.department = :department')
            ->andWhere('s.program = :program')
            ->andWhere('s.semester = :semester')
            ->setParameter('department', $department)
            ->setParameter('program', $program)
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
        if (count($courses) > 0){
            $arrayOfCourses = $courses[0]->getCourseIds();
            $jsonArray = array();
            foreach ($arrayOfCourses as $value) {
                $course = $this->getDoctrine()->getRepository(Courses::class)->findBy([
                    'id' => $value
                ]);

                $jsonArray[] = [
                    'id' => $course[0]->getId(),
                    'course' => $course[0]->getCourse(),
                    'slug' => $course[0]->getSlug(),
                    'code' => $course[0]->getCourseCode()
                ];
            }
                return new JsonResponse($jsonArray);
        }else{
                return new JsonResponse(array());
        }
        //        dump();
        //        die();
    }//getCourseFunction ends here

}