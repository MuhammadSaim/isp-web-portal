<?php

namespace App\Controller\Dashboard\Assignment;


use App\Entity\Assignments;
use App\Entity\AssignmentSubmissions;
use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\SemesterCourseMapping;
use App\Entity\Semesters;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Form\AddAssignmentType;
use App\Repository\TeacherCourseMappingRepository;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AssignmentController
 * @package App\Controller\Dashboard\Assignment
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
 * @Route("/assignment", name="assignment_")
 */
class AssignmentController extends AbstractController
{

    private $helperFunction;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunction = $helperFunction;
    }

    /**
     * @Route("/", name="all")
     */
    public function assignments()
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            $topFiveAssignments = $this->getDoctrine()->getRepository(Assignments::class)->getTopAssignmentsOfTeacher($this->getUser());
            $courses = $this->getUniqueCourses();
//            dump($courses);
//            die();
            return $this->render('dashboard/assignment/top_assignments.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'courses' => $courses,
                'topFiveAssignments' => $topFiveAssignments,
                'active' => ''
            ]);
        }
        $topFiveAssignments = $this->getDoctrine()->getRepository(Assignments::class)->getTopAssignmentsOfStudents($this->getUser()->getStudentDetails());
        $courses = $this->getDoctrine()->getRepository(SemesterCourseMapping::class)->findBy([
            'department' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getDepartment(),
            'program' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getProgram(),
            'semester' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getSemester()
        ]);
        return $this->render('dashboard/assignment/top_assignments.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'coursesIds' => $courses[0]->getCourseIds(),
            'course' => $courses[0],
            'topFiveAssignments' => $topFiveAssignments,
            'active' => ''
        ]);
    }

    /**
     * @Route("/{departmentId}/{programId}/{semesterId}/{courseSlug}", name="courses_assignments")
     */
    public function assignmentAccordingToCourses($departmentId, $programId, $semesterId, $courseSlug)
    {
        if ($this->isGranted('ROLE_STUDENT')) {
            $assignments = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
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
            return $this->render('dashboard/assignment/all_assignments.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'coursesIds' => $courses[0]->getCourseIds(),
                'course' => $courses[0],
                'assignments' => $assignments,
                'active' => $courseSlug
            ]);
        }
        if($this->isGranted('ROLE_TEACHER')){
            $assignments = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
                'department' => $departmentId,
                'program' => $programId,
                'semester' => $semesterId,
                'course'   => $this->getDoctrine()->getRepository(Courses::class)->findBy(['slug' => $courseSlug]),
                'teacher' => $this->getUser()
            ]);
            $courses = $this->getUniqueCourses();
            return $this->render('dashboard/assignment/all_assignments.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'courses'     => $courses,
                'assignments'    => $assignments,
                'active'      => $courseSlug
            ]);
        }
    }

    /**
     * @Route("/show/{assignmentSlug}", name="show")
     */
    public function assignment($assignmentSlug){
        if($this->isGranted('ROLE_TEACHER')){
            $assignment = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
                'slug' => $assignmentSlug,
                'teacher' => $this->getUser()
            ]);
            return $this->render('dashboard/assignment/show_assignment.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'assignment'  => $assignment[0]
            ]);
        }else{
            $assignment = $this->getDoctrine()->getRepository(Assignments::class)->getAssignment($assignmentSlug, $this->getUser());
            if($assignment == null){
                $assignment = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
                    'slug' => $assignmentSlug
                ]);
            }
            return $this->render('dashboard/assignment/show_assignment.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'assignment'  => $assignment[0]
            ]);
        }
    }


    /**
     * @Route("/submit/{assignmentSlug}", name="submit")
     */
    public function submitAssignment(Request $request, $assignmentSlug)
    {
        $assignment = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
            'slug' => $assignmentSlug
        ]);
        if($request->isMethod('POST')){
            $fileToAllow = array('docx', 'doc', 'pdf', 'csv', 'ppt', 'pptx', 'zip', 'rar');
            $file = $request->files->get('assignment_file');
            $extension = $file->guessExtension();
            $fileSize = $this->helperFunction->convert_filesize($file->getSize(), 0);
            $client_name = $file->getClientOriginalName();
            $server_name = $this->helperFunction->slugify($client_name).'-'.$this->helperFunction->getUiqueName().'.'.$extension;
            if(!in_array($extension, $fileToAllow)){
                $this->addFlash('danger', $extension.' is not allowed to upload');
                return $this->render('dashboard/assignment/submit_assignment.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'assignment'  => $assignment[0]
                ]);
            }
            if($file->getSize() > 5000000){
                $this->addFlash('danger', 'File size is to large');
                return $this->render('dashboard/assignment/submit_assignment.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'assignment'  => $assignment[0]
                ]);
            }
            try{
                $file->move(
                    $this->getParameter('assignment_submit_upload'),
                    $server_name
                );
                $assignmentSubmission = new AssignmentSubmissions();
                $assignmentSubmission->setStudent($this->getUser());
                $assignmentSubmission->setFileSize($fileSize);
                $assignmentSubmission->setAssignment($assignment[0]);
                $assignmentSubmission->setFileClientName($client_name);
                $assignmentSubmission->setFileServerName($server_name);
                $assignmentSubmission->setFileExtenstion($extension);
                $assignmentSubmission->setCreatedAt(new \DateTime());
                $assignmentSubmission->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($assignmentSubmission);
                $em->flush();
                return $this->redirectToRoute('assignment_show', ['assignmentSlug' => $assignmentSlug]);

            }catch (FileException $e){
                $this->addFlash('danger', 'There is something wrong please try again');
                return $this->render('dashboard/assignment/submit_assignment.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'assignment'  => $assignment[0]
                ]);
            }
        }
        return $this->render('dashboard/assignment/submit_assignment.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'assignment'  => $assignment[0]
        ]);
    }


    /**
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     * @Route("/add", name="add")
     */
    public function addAssignemnt(Request $request)
    {
        $fileToAllow = array('jpg', 'jpeg', 'png', 'gif', 'docx', 'doc', 'pdf', 'csv', 'ppt', 'pptx');
        $assignment = new Assignments();
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
        if($request->isMethod('post')){
            $file = $request->files->get('assignment_file');
            if($file != null){
                $file = $request->files->get('lecture');
                $extension = $file->guessExtension();
                $fileSize = $this->helperFunction->convert_filesize($file->getSize(), 0);
                $client_name = $file->getClientOriginalName();
                $server_name = $this->helperFunction->slugify($client_name).'-'.$this->helperFunction->getUiqueName().'.'.$extension;
                if(!in_array($extension, $fileToAllow)){
                    $this->addFlash('danger', $extension.' is not allowed to upload');
                    return $this->render('dashboard/assignment/add_assigment.html.twig', [
                        'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                        'data'        => $data
                    ]);
                }
               try{
                   $file->move(
                       $this->getParameter('assignment_upload'),
                       $server_name.'.'.$extension
                   );
                   $assignment->setFileServerName($server_name);
                   $assignment->setFileClientName($client_name);
                   $assignment->setFileSize($fileSize);
                   $assignment->setFileExtension($extension);
               }catch (FileException $e){
                   $this->addFlash('danger', 'There is something wrong please try again.');
                   return $this->render('dashboard/assignment/add_assigment.html.twig', [
                       'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                       'data'        => $data
                   ]);
               }
            }
            if(!empty($request->request->get('description'))){
                $assignment->setAssignmentDescription($request->request->get('description'));
            }
            if(strtotime($request->request->get('submission_date')) < strtotime($request->request->get('start_date'))){
                $this->addFlash('danger', 'Please chose proper dates.');
                return $this->render('dashboard/assignment/add_assigment.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'data'        => $data
                ]);
            }
            $assignment_title = $request->request->get('assignment_title');
            $slug = $this->helperFunction->slugify($assignment_title);
            $checkAssignment = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
                'slug' => $slug
            ]);

            if($checkAssignment != null){
                $slug = $slug.'-'.$this->helperFunction->getUiqueName();
            }

            $assignment->setTeacher($this->getUser());
            $assignment->setSection($this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section')));
            $assignment->setProgram($this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program')));
            $assignment->setSemester($this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester')));
            $assignment->setDepartment($this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department')));
            $assignment->setCourse($this->getDoctrine()->getRepository(Courses::class)->find($request->request->get('course')));
            $assignment->setStartDate(new \DateTime($request->request->get('start_date')));
            $assignment->setSubmissionDate(new \DateTime($request->request->get('submission_date')));
            $assignment->setTotalMarks($request->request->get('total_marks'));
            $assignment->setAssignmentTitle($assignment_title);
            $assignment->setSlug($slug);
            $assignment->setCreatedAt(new \DateTime());
            $assignment->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            $em->flush();
            $this->addFlash('success', 'Assignment is added successfully');
            return $this->render('dashboard/assignment/add_assigment.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'data'        => $data
            ]);

        }
        return $this->render('dashboard/assignment/add_assigment.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'data'        => $data
        ]);
    }

    /**
     * @Route("/evaluate/{assignmentSlug}", name="evaluation")
     */
    public function assignmentEvaluation(Request $request, $assignmentSlug)
    {
        $assignemnt = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
            'slug' => $assignmentSlug
        ]);
        if($assignemnt == null){
            throw new NotFoundHttpException();
        }
        $submitedAssignments = $this->getDoctrine()->getRepository(AssignmentSubmissions::class)->findBy([
            'assignment' => $assignemnt
        ]);
        if($request->isMethod('POST')){
            $allrecord = array_chunk($request->request->get('evaluateForm'), 2);
            foreach ($allrecord as $record){
                $assSubmit = $this->getDoctrine()->getRepository(AssignmentSubmissions::class)->find($record[0]);
                $assSubmit->setObtainedMarks($record[1]);
                $em = $this->getDoctrine()->getManager();
                $em->persist($assSubmit);
                $em->flush();
            }
            return $this->render('dashboard/assignment/avaluate_assignmnets.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'assignments' => $submitedAssignments
            ]);
        }
        return $this->render('dashboard/assignment/avaluate_assignmnets.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'assignments' => $submitedAssignments
        ]);
    }

    /**
     * @Route("/submited/{assignmentSlug}", name="submitted")
     */
    public function assignmentSubmitted(Request $request, $assignmentSlug)
    {
        $assignemnt = $this->getDoctrine()->getRepository(Assignments::class)->findBy([
            'slug' => $assignmentSlug
        ]);
        if($assignemnt == null){
            throw new NotFoundHttpException();
        }
        $submitedAssignments = $this->getDoctrine()->getRepository(AssignmentSubmissions::class)->findBy([
            'assignment' => $assignemnt
        ]);
        return $this->render('dashboard/assignment/assignment_list.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'assignments' => $submitedAssignments
        ]);
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