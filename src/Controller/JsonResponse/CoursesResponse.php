<?php

namespace App\Controller\JsonResponse;

use App\Entity\Departments;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Courses;

/**
 * Class CoursesResponse
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/api/courses", name="api_courses_")
 */
class CoursesResponse extends AbstractController
{

    /**
     * @Route("/department/{departmentId}", name="department")
     */
    public function getCoursesWithDepartment($departmentId)
    {

        $em = $this->getDoctrine()->getManager();

        $repoCourses = $em->getRepository(Courses::class);

        $courses = $repoCourses->findBy([
            'department' => $em->getRepository(Departments::class)->findOneBy(['id' => $departmentId])
        ]);

        $jsonCoursesArray = array();

        foreach ($courses as $course){
            $jsonCoursesArray[] = array(
                'id' => $course->getId(),
                'slug' => $course->getSlug(),
                'course' => $course->getCourse()
            );
        }

        return new JsonResponse($jsonCoursesArray);
    }

}