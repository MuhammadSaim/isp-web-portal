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
use App\Entity\ExamsStatus;
use App\Entity\Sections;
use App\Entity\SemesterCourseMapping;
use App\Entity\Semesters;
use App\Entity\StaffDetails;
use App\Entity\StudentDetails;
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


    /**
     * @Route("/open-mid", name="mid_open")
     */
    public function openMidTermStatus()
    {
        $examStatus = $this->getDoctrine()->getRepository(ExamsStatus::class)->findAll();
        $examStatus[0]->setStatus(1);
        $examStatus[1]->setStatus(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($examStatus[0]);
        $em->persist($examStatus[1]);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }

    /**
     * @Route("/close-mid", name="mid_close")
     */
    public function closeMidTermStatus()
    {
        $exam = $this->getDoctrine()->getRepository(ExamsStatus::class)->find(1);
        $exam->setStatus(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($exam);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }

    /**
     * @Route("/close-final", name="final_close")
     */
    public function closeFinalTermStatus()
    {
        $exam = $this->getDoctrine()->getRepository(ExamsStatus::class)->find(2);
        $exam->setStatus(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($exam);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }

    /**
     * @Route("/open-final", name="final_open")
     */
    public function openFinalTermStatus()
    {
        $examStatus = $this->getDoctrine()->getRepository(ExamsStatus::class)->findAll();
        $examStatus[0]->setStatus(0);
        $examStatus[1]->setStatus(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($examStatus[0]);
        $em->persist($examStatus[1]);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }


    /**
     * @Route("/public-phone-staff", name="public_phone_staff")
     */
    public function staffPublicPhoneNumber()
    {
        $user = $this->getDoctrine()->getRepository(StaffDetails::class)->findOneBy([
            'user' => $this->getUser()
        ]);
        $user->setIsPhoneAvailable(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }

    /**
     * @Route("/public-phone-staff-off", name="public_phone_staff_close")
     */
    public function staffPublicPhoneNumberClose()
    {
        $user = $this->getDoctrine()->getRepository(StaffDetails::class)->findOneBy([
            'user' => $this->getUser()
        ]);
        $user->setIsPhoneAvailable(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }


    /**
     * @Route("/public-phone-student", name="public_phone_student")
     */
    public function studentPublicPhoneNumber()
    {
        $user = $this->getDoctrine()->getRepository(StudentDetails::class)->findOneBy([
            'user' => $this->getUser()
        ]);
        $user->setIsPhoneAvailable(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }

    /**
     * @Route("/public-phone-student-off", name="public_phone_student_close")
     */
    public function studentPublicPhoneNumberClose()
    {
        $user = $this->getDoctrine()->getRepository(StudentDetails::class)->findOneBy([
            'user' => $this->getUser()
        ]);
        $user->setIsPhoneAvailable(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse(array('status' => true));
    }


    /**
     * @Route("/enable/status", name="enable_status")
     */
    public function enableExamsStatus()
    {

    }

    /**
     * @Route("/disable/status", name="disable_status")
     */
    public function disableExamsStatus()
    {

    }

}