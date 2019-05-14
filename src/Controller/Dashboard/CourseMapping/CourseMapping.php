<?php

namespace App\Controller\Dashboard\CourseMapping;

use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\SemesterCourseMapping;
use App\Entity\Semesters;
use App\Entity\Sessions;
use App\Entity\TeacherCourseMapping;
use App\Entity\User;
use App\Form\SemesterCourseMappingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
                return $this->redirectToRoute('course_mapping_semester');
            }
            $departmentId = $data['department'];
            $programId = $data['program'];
            $findProgram = $this->getDoctrine()->getRepository(Programs::class)->findValueWithDepart($departmentId, $programId);
            if(empty($findProgram)){
                $this->addFlash('danger', 'Chose program according to the department');
                return $this->redirectToRoute('course_mapping_semester');
            }
            $findRecord = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->recordExists($departmentId, $programId, $data['semester']);
            if(!empty($findRecord)){
                $this->addFlash('danger', 'This mapping is already exists.');
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
     * @Route("/course-mapping-all", name="all_course_mapping")
     */
    public function allSemesterCourseMapping()
    {
        $courses = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->findAll();
        return $this->render('dashboard/courseMapping/all_semester_course_mapping.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'courses'     => $courses
        ]);
    }


    /**
     * @Route("/update/semester-mapping/{id}", name="update_semester_mapping")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function updateSemesterCourseMapping(Request $request, $id)
    {
        $mapping = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->find($id);
        $programs = $this->getDoctrine()->getRepository(Programs::class)->findAll();
        $semester = $this->getDoctrine()->getRepository(Semesters::class)->findAll();
        $courses  =$this->getDoctrine()->getRepository(Courses::class)->findAll();
        if($mapping == null){
            throw new NotFoundHttpException();
        }
        if($request->isMethod('POST')){
            $department = $request->request->get('department');
            $program    = $request->request->get('program');
            $semester   = $request->request->get('semester');
            $courses    = $request->request->get('courses');
            if(count($courses) < 2 || count($courses) > 7){
                $this->addFlash('danger', 'courses length should be in 2 to 7.');
                return $this->redirectToRoute("course_mapping_update_semester_mapping", ['id' => $id]);
            }
            $findProgram = $this->getDoctrine()->getRepository(Programs::class)->findValueWithDepart($department, $program);
            if(empty($findProgram)){
                $this->addFlash('danger', 'Chose program according to the department');
                return $this->redirectToRoute("course_mapping_update_semester_mapping", ['id' => $id]);
            }
            $mapping->setDepartment($this->getDoctrine()->getRepository(Departments::class)->find($department));
            $mapping->setProgram($this->getDoctrine()->getRepository(Programs::class)->find($program));
            $mapping->setSemester($this->getDoctrine()->getRepository(Semesters::class)->find($semester));
            $mapping->setCourseIds($courses);
            $mapping->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($mapping);
            $em->flush();
            $this->addFlash('success', 'Mapping is updated successfully.');
            return $this->redirectToRoute("course_mapping_update_semester_mapping", ['id' => $id]);
        }
        return $this->render(
            "dashboard/courseMapping/update_semester_course_mapping.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'programs'    => $programs,
            'semesters'   => $semester,
            'courses'   => $courses,
            'mapping'     => $mapping
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
            $teacherCourseMapping->setSession($this->getDoctrine()->getRepository(Sessions::class)->findLastInserted());
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

    /**
     * @Route("/teachers-mapping-all", name="all_teacher_course_mapping")
     */
    public function allTeacherMappingDetails()
    {
        $teacherMapping = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->findBy([
            'session' => $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted()
        ]);
        return $this->render('dashboard/courseMapping/all_teacher_mapping.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'mapping'     => $teacherMapping
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/update-teacher-map/{id}", name="update_teacher_map")
     */
    public function updateTeacherCourseMapping(Request $request, $id)
    {
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        $programs = $this->getDoctrine()->getRepository(Programs::class)->findAll();
        $semesters = $this->getDoctrine()->getRepository(Semesters::class)->findAll();
        $sections = $this->getDoctrine()->getRepository(Sections::class)->findAll();
        $teachers = $this->getDoctrine()->getRepository(User::class)->findTeacher('ROLE_TEACHER');
        $courses = $this->getDoctrine()->getRepository(Courses::class)->findAll();
        $map = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->find($id);
        if($map == null){
            throw new NotFoundHttpException();
        }
        if($request->isMethod('POST')){
            $department = $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department'));
            $program = $this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program'));
            $semester = $this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester'));
            $section = $this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section'));
            $course = $this->getDoctrine()->getRepository(Courses::class)->find($request->request->get('course'));
            $teacher = $this->getDoctrine()->getRepository(User::class)->find($request->request->get('teacher'));
            $map->setDepartment($department);
            $map->setProgram($program);
            $map->setSemester($semester);
            $map->setSection($section);
            $map->setCourse($course);
            $map->setTeacher($teacher);
            $map->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($map);
            $em->flush();
            $this->addFlash('success', 'Teacher mapping updated successfully.');
            return $this->redirectToRoute('course_mapping_update_teacher_map', ['id' => $id]);

        }
        return $this->render('dashboard/courseMapping/update_teacher_course_mapping.html.twig', [
            'departments' => $departments,
            'mapping'     => $map,
            'programs'    => $programs,
            'semesters'   => $semesters,
            'sections'    => $sections,
            'teachers'    => $teachers,
            'courses'     => $courses
        ]);
    }

}