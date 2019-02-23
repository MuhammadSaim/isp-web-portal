<?php

namespace App\Controller\Dashboard\CourseMapping;

use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\SemesterCourseMapping;
use App\Form\SemesterCourseMappingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class courseMapping
 * @Route("/course-mapping", name="course_mapping_")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 */
class CourseMapping extends AbstractController
{

    /**
     * @Route("/semester-add", name="semester")
     */
    public function semesterCourseMapping(Request $request)
    {
        $semesterCourseMapping = new SemesterCourseMapping();
        $form = $this->createForm(SemesterCourseMappingType::class, []);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            if(count($data['courses']) < 2 || count($data['courses']) > 7){
                $this->addFlash('danger', 'courses length should be in 2 to 7.');
//                return $this->render('dashboard/courseMapping/add_semester_course_mapping.html.twig',[
//                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
//                    'form'        => $form->createView()
//                ]);
                return $this->redirectToRoute('course_mapping_semester');
            }

            $departmentId = $data['department'];
            $programId = $data['program'];

            $findProgram = $this->getDoctrine()->getRepository(Programs::class)->findValueWithDepart($departmentId, $programId);
            if(empty($findProgram)){
                $this->addFlash('danger', 'Chose program according to the department');
//                return $this->render('dashboard/courseMapping/add_semester_course_mapping.html.twig',[
//                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
//                    'form'        => $form->createView()
//                ]);
                return $this->redirectToRoute('course_mapping_semester');
            }

            $findRecord = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->recordExists($departmentId, $programId, $data['semester']);
            if(!empty($findRecord)){
                $this->addFlash('danger', 'This mapping is already exists.');
//                return $this->render('dashboard/courseMapping/add_semester_course_mapping.html.twig',[
//                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
//                    'form'        => $form->createView()
//                ]);
                return $this->redirectToRoute('course_mapping_semester');
            }
            $courses = array();

            foreach ($data['courses'] as $value){
                $courses[] = $value->getId();
            }

            $semesterCourseMapping->setDepartment($departmentId);
            $semesterCourseMapping->setProgram($programId);
            $semesterCourseMapping->setCourseIds($courses);
            $semesterCourseMapping->setSemester($data['semester']);
            $semesterCourseMapping->setCreatedAt(new \DateTime());
            $semesterCourseMapping->setModifiedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();

            $em->persist($semesterCourseMapping);
            $em->flush();

            $this->addFlash('success', 'Course Mapping is added successfully!');


        }
        return $this->render(
            "dashboard/courseMapping/add_semester_course_mapping.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'form'        => $form->createView()
        ]);
    }

}