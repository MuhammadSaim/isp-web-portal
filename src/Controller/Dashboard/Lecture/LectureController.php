<?php

namespace App\Controller\Dashboard\Lecture;


use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Lectures;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\SemesterCourseMapping;
use App\Entity\Semesters;
use App\Entity\Sessions;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LectureController
 * @package App\Controller\Dashboard\Lecture
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/lectures", name="lectures_")
 */
class LectureController extends AbstractController
{


    private $helperFunction;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunction = $helperFunction;
    }

    /**
     * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
     * @Route("/", name="all")
     */
    public function lectures()
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            $topFiveLectures = $this->getDoctrine()->getRepository(Lectures::class)->getTopLecturesOfTeacher($this->getUser());
            $courses = $courses = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->findBy([
                'teacher' => $this->getUser(),
                'session' => $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted()
            ]);;
//            dump($topFiveLectures);
//            die();
            return $this->render('dashboard/lecture/top_five_lectures.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'courses' => $courses,
                'topFiveLectures' => $topFiveLectures,
                'active' => ''
            ]);
        }

        $topFiveLectures = $this->getDoctrine()->getRepository(Lectures::class)->getTopLecturesOfStudents($this->getUser()->getStudentDetails());
        $courses = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->findBy([
            'department' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getDepartment(),
            'program' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getProgram(),
            'semester' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getSemester()
        ]);
        return $this->render('dashboard/lecture/top_five_lectures.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'coursesIds' => $courses[0]->getCourseIds(),
            'course' => $courses[0],
            'topFiveLectures' => $topFiveLectures,
            'active' => ''
        ]);

    }

    /**
     * @Security("is_granted(['ROLE_TEACHER'])", statusCode=404)
     * @Route("/add", name="add")
     */
    public function addLecture(Request $request)
    {
        $teacherRepo = $this->getDoctrine()->getRepository(TeacherCourseMapping::class);
        $courses = $teacherRepo->getDistinctCourses($this->getUser());
        $program = $teacherRepo->getDistinctProgram($this->getUser());
        $department = $teacherRepo->getDistinctDepartment($this->getUser());
        $semester = $teacherRepo->getDistinctSemester($this->getUser());
        $section = $teacherRepo->getDistinctSection($this->getUser());
        $data = [
            'courses'    => $courses,
            'programs'    => $program,
            'departments' => $department,
            'semesters'   => $semester,
            'sections'    => $section
        ];
        if ($request->isMethod('POST')) {
            $fileToAllow = array('jpg', 'jpeg', 'png', 'gif', 'docx', 'doc', 'pdf', 'csv', 'ppt', 'pptx');
            $department = $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department'));
            $program = $this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program'));
            $course = $this->getDoctrine()->getRepository(Courses::class)->find($request->request->get('course'));
            $semester = $this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester'));
            $section = $this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section'));
            $file = $request->files->get('lecture');
            $extension = $file->guessExtension();
            $fileSize = $this->helperFunction->convert_filesize($file->getSize(), 0);
            $client_name = $file->getClientOriginalName();
            $server_name = $this->helperFunction->slugify($client_name).'-'.$this->helperFunction->getUiqueName().'.'.$extension;
            if(!in_array($extension, $fileToAllow)){
                $this->addFlash('danger', $extension.' is not allowed to upload');
                return $this->render('dashboard/lecture/add_lecture.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'data'        => $data
                ]);
            }
            try{
                $file->move(
                    $this->getParameter('lecture_upload'),
                    $server_name.'.'.$extension
                );
                $lecture = new Lectures();
                $lecture->setDepartment($department);
                $lecture->setProgram($program);
                $lecture->setCourse($course);
                $lecture->setSemester($semester);
                $lecture->setSection($section);
                $lecture->setTeacher($this->getUser());
                $lecture->setFileServerName($server_name);
                $lecture->setFileClientName($client_name);
                $lecture->setFileSize($fileSize);
                $lecture->setFileExtension($extension);
                $lecture->setCreatedAt(new \DateTime());
                $lecture->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($lecture);
                $em->flush();
                $this->addFlash('success', 'File is uploaded successfully');
                return $this->render('dashboard/lecture/add_lecture.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'data'        => $data
                ]);
            }catch (FileException $e){
                $this->addFlash('danger', 'There is something wrong please try again.');
                return $this->render('dashboard/lecture/add_lecture.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'data'        => $data
                ]);
            }
        }
        return $this->render('dashboard/lecture/add_lecture.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'data'        => $data
        ]);
    }


    /**
     * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
     * @Route("/{departmentId}/{programId}/{semesterId}/{courseSlug}", name="show_lectuers")
     */
    public function lecture($departmentId, $programId, $semesterId, $courseSlug)
    {
        if ($this->isGranted('ROLE_STUDENT')) {
            $lectures = $this->getDoctrine()->getRepository(Lectures::class)->findBy([
                'department' => $departmentId,
                'program' => $programId,
                'semester' => $semesterId,
                'course'   => $this->getDoctrine()->getRepository(Courses::class)->findBy(['slug' => $courseSlug])
            ]);
            $courses = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->findBy([
                'department' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getDepartment(),
                'program' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getProgram(),
                'semester' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getSemester(),
            ]);
            return $this->render('dashboard/lecture/all_lectures.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'coursesIds' => $courses[0]->getCourseIds(),
                'course' => $courses[0],
                'lectures' => $lectures,
                'active' => $courseSlug
            ]);
        }
        if($this->isGranted('ROLE_TEACHER')){
            $lectures = $this->getDoctrine()->getRepository(Lectures::class)->findBy([
                'department' => $departmentId,
                'program' => $programId,
                'semester' => $semesterId,
                'course'   => $this->getDoctrine()->getRepository(Courses::class)->findBy(['slug' => $courseSlug]),
                'teacher' => $this->getUser()
            ]);
            $courses = $courses = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->findBy([
                'teacher' => $this->getUser(),
                'session' => $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted()
            ]);
            return $this->render('dashboard/lecture/all_lectures.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'courses'     => $courses,
                'lectures'    => $lectures,
                'active'      => $courseSlug
            ]);
        }
    }


    /**
     * @param $slug
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteLectures($id)
    {
        $lecture = $this->getDoctrine()->getRepository(Lectures::class)->find($id);
        if($lecture == null){
            throw new NotFoundHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($lecture);
        $em->flush();
        return $this->redirectToRoute('lectures_all');
    }

    private function getUniqueCourses()
    {
        $slugs = [];
        $uniqueSlugs = [];
        $uniqueCourses = [];
        $courses = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->findBy([
            'teacher' => $this->getUser()
        ]);
        foreach ($courses as $course){
            $slugs[] = $course->getCourse()->getSlug();
        }
        $uniqueSlugs = array_unique($slugs);
        for($i=0; $i<count($uniqueSlugs); $i++){
            if($courses[$i]->getCourse()->getSlug() === $uniqueSlugs[$i]){
                $uniqueCourses[] = $courses[$i];
            }
        }
        return $uniqueCourses;
    }


}