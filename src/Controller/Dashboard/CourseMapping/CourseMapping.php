<?php

namespace App\Controller\Dashboard\CourseMapping;

use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\SemesterCourseMapping;
use App\Entity\Semesters;
use App\Entity\TeacherCourseMapping;
use App\Entity\User;
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
        $semesterCourseMapping = new CourseMapping();
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

            $findRecord = $this->getDoctrine()->getRepository(CourseMapping::class)->recordExists($departmentId, $programId, $data['semester']);
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


    /**
     * @Route("/teacher-add", name="teacher")
     */
    public function teacherCourseMapping(Request $request)
    {
        $teacherCourseMapping = new TeacherCourseMapping();
        if($request->isMethod('post')){
            $department = $request->request->get('department');
            $program = $request->request->get('program');
            $semester = $request->request->get('semester');
            $section = $request->request->get('section');
            $teacher = $request->request->get('teacher');
            $course = $request->request->get('course');

            $tcmRepo = $this->getDoctrine()->getRepository(TeacherCourseMapping::class);
            $check_data = $tcmRepo->createQueryBuilder('t')
                                  ->andWhere('t.course = :course')
                                  ->andWhere('t.department = :department')
                                  ->andWhere('t.program = :program')
                                  ->andWhere('t.section = :section')
                                  ->andWhere('t.teacher = :teacher')
                                  ->andWhere('t.semester = :semester')
                                  ->setParameter('course', $course)
                                  ->setParameter('department', $department)
                                  ->setParameter('program', $program)
                                  ->setParameter('section', $section)
                                  ->setParameter('semester', $semester)
                                  ->setParameter('teacher', $teacher)
                                  ->getQuery()
                                  ->getResult();


            if(!empty($check_data)){
                $this->addFlash('danger', 'This teacher mapping is already exists.');
                return $this->render(
                    "dashboard/courseMapping/add_teacher_course_mapping.html.twig",[
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                ]);
            }

            $teacherCourseMapping->setSemester($this->getDoctrine()->getRepository(Semesters::class)->find($semester));
            $teacherCourseMapping->setProgram($this->getDoctrine()->getRepository(Programs::class)->find($program));
            $teacherCourseMapping->setDepartment($this->getDoctrine()->getRepository(Departments::class)->find($department));
            $teacherCourseMapping->setCourse($this->getDoctrine()->getRepository(Courses::class)->find($course));
            $teacherCourseMapping->setTeacher($this->getDoctrine()->getRepository(User::class)->find($teacher));
            $teacherCourseMapping->setSection($this->getDoctrine()->getRepository(Sections::class)->find($section));
            $teacherCourseMapping->setCreatedAt(new \DateTime());
            $teacherCourseMapping->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacherCourseMapping);
            $em->flush();


            $this->addFlash('success', 'Mapping is added successfully.');
        }

        return $this->render(
            "dashboard/courseMapping/add_teacher_course_mapping.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
        ]);
    }

}