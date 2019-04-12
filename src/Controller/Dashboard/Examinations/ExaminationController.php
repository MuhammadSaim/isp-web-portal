<?php

namespace App\Controller\Dashboard\Examinations;


use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Examination;
use App\Entity\ExamsStatus;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\Semesters;
use App\Entity\Sessions;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ExaminationController
 * @package App\Controller\Dashboard\Examinations
 * @Route("/exams", name="exams_")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 */
class ExaminationController extends AbstractController
{
    /**
     * @Route("/all", name="all")
     */
    public function allTheSubjects()
    {
        if($this->isGranted('ROLE_TEACHER')){
            $courses = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->findBy([
                'teacher' => $this->getUser()
            ]);

            return $this->render("dashboard/Examination/all_courses.html.twig", [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'courses'     => $courses
            ]);

        }

    }


    /**
     * @param Request $request
     * @Route("/{course}/add", name="add")
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     */
    public function addResult(Request $request, $course)
    {
        $exam_statuses_mid = $this->getDoctrine()->getRepository(ExamsStatus::class)->findStatusMid();
        $exam_statuses_final = $this->getDoctrine()->getRepository(ExamsStatus::class)->findStatusFinal();
        $tcm = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->find($course);
        if($tcm == null){
            throw new NotFoundHttpException();
        }
        if($exam_statuses_final == null && $exam_statuses_mid == null){
            return $this->render('dashboard/Examination/add_results.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'message'     => 'Wait for open results.',
                'result'      => false
            ]);
        }
        if($exam_statuses_mid != null && $exam_statuses_final == null){
            $session = $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted();
            $students = $this->getDoctrine()->getRepository(Examination::class)->findBy([
                'department' => $tcm->getDepartment(),
                'program'    => $tcm->getProgram(),
                'semester'   => $tcm->getSemester(),
                'section'    => $tcm->getSection(),
                'course'     => $tcm->getCourse(),
                'session'    => $session
            ]);
            if(count($students) > 0) {
                return $this->render('dashboard/Examination/add_results.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'message'     => 'Result is already added.',
                    'result'      => false
                ]);
            }
            $students = $this->getDoctrine()->getRepository(StudentDetails::class)->findBy([
                'department' => $tcm->getDepartment(),
                'program'    => $tcm->getProgram(),
                'semester'   => $tcm->getSemester(),
                'section'    => $tcm->getSection(),
            ]);
            return $this->render('dashboard/Examination/add_mid_results.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'students'    => $students,
                'course'      => $course
            ]);
        }
        if($exam_statuses_mid == null && $exam_statuses_final != null){
            $session = $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted();
            $check_final = $this->getDoctrine()->getRepository(Examination::class)->findFinalResults([
                'department' => $tcm->getDepartment(),
                'program'    => $tcm->getProgram(),
                'semester'   => $tcm->getSemester(),
                'section'    => $tcm->getSection(),
                'course'     => $tcm->getCourse(),
                'session'    => $session
            ]);
            if(count($check_final) > 0){
                return $this->render('dashboard/Examination/add_results.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'message'     => 'Result is already added.',
                    'result'      => false
                ]);
            }
            $students = $this->getDoctrine()->getRepository(Examination::class)->findBy([
                'department' => $tcm->getDepartment(),
                'program'    => $tcm->getProgram(),
                'semester'   => $tcm->getSemester(),
                'section'    => $tcm->getSection(),
                'course'     => $tcm->getCourse(),
                'session'    => $session
            ]);
//            dump($students);
//            die();
            return $this->render('dashboard/Examination/add_final_results.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'students'    => $students,
                'course'      => $course
            ]);
        }
    }

    /**
     * @Route("/{course}/mid/save", name="save_mid")
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     */
    public function saveMidresult(Request $request, $course)
    {
        if($request->isMethod('POST')){
            $session = $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted();
            $allrecord = array_chunk($request->request->get('midMarks'), 2);
            $tcm = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->find($course);
            foreach ($allrecord as $record){
                $exam = new Examination();
                $exam->setMid($record[0]);
                $exam->setStudent($this->getDoctrine()->getRepository(User::class)->find($record[1]));
                $exam->setDepartment($tcm->getDepartment());
                $exam->setProgram($tcm->getProgram());
                $exam->setCourse($tcm->getCourse());
                $exam->setSemester($tcm->getSemester());
                $exam->setSection($tcm->getSection());
                $exam->setTeacher($this->getUser());
                $exam->setSession($session);
                $exam->setDate(new \DateTime());
                $exam->setCreatedAt(new \DateTime());
                $exam->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($exam);
                $em->flush();
            }
            $this->addFlash('success', 'Mid result is added successfully!');
            return $this->redirectToRoute('exams_all');
        }
    }

    /**
     * @param Request $request
     * @param $course
     * @Route("/{course}/final/save", name="save_final")
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     */
    public function saveFinalResult(Request $request, $course)
    {
        if($request->isMethod('POST')){
//            $session = $this->getDoctrine()->getRepository(Sessions::class)->findLastInserted();
            $allrecord = array_chunk($request->request->get('finalMarks'), 3);
//            $tcm = $this->getDoctrine()->getRepository(TeacherCourseMapping::class)->find($course);
            foreach ($allrecord as $record){
                $exam = $this->getDoctrine()->getRepository(Examination::class)->find($record[2]);
                $exam->setSestional($record[0]);
                $exam->setFinal($record[1]);
                $exam->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($exam);
                $em->flush();
            }
            $this->addFlash('success', 'Final result is added successfully!');
            return $this->redirectToRoute('exams_all');
        }
    }
    
    /**
     * @Route("/open-exam", name="open")
     */
    public function openExam()
    {
        return $this->render('dashboard/Examination/open_exam.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'exams'       => $this->getDoctrine()->getRepository(ExamsStatus::class)->findAll()
        ]);
    }

    /**
     * @Route("/details", name="details")
     */
    public function examDetails(Request $request)
    {
        if($request->isMethod('POST')){
            $allRecords = $this->getDoctrine()->getRepository(Examination::class)->findBy([
                'department' => $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department')),
                'program' => $this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program')),
                'course' => $this->getDoctrine()->getRepository(Courses::class)->find($request->request->get('course')),
                'semester' => $this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester')),
                'section' => $this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section')),
                'session' => $this->getDoctrine()->getRepository(Sessions::class)->find($request->request->get('session')),
            ]);
            return $this->render('dashboard/Examination/all_detailed_records.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'records'     => $allRecords
            ]);
        }
        $department = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        $program = $this->getDoctrine()->getRepository(Programs::class)->findAll();
        $courses = $this->getDoctrine()->getRepository(Courses::class)->findAll();
        $semester = $this->getDoctrine()->getRepository(Semesters::class)->findAll();
        $section = $this->getDoctrine()->getRepository(Sections::class)->findAll();
        $sessions = $this->getDoctrine()->getRepository(Sessions::class)->findAllSessionsDesc();
        $data = [
            'courses'    => $courses,
            'programs'    => $program,
            'departments' => $department,
            'semesters'   => $semester,
            'sections'    => $section,
            'sessions'    => $sessions
        ];
        return $this->render('dashboard/Examination/exam_details_form.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'data' => $data
        ]);
    }


    /**
     * @param Request $request
     * @Route("/update/{id}", name="update_details")
     */
    public function updateExamDetails(Request $request, $id)
    {
        $record = $this->getDoctrine()->getRepository(Examination::class)->find($id);

        if($request->isMethod('POST')){
            if($record->getSestional() == null && $record->getFinal() == null){
                $record->setMid($request->request->get('mid'));
                $record->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($record);
                $em->flush();
                $this->addFlash('success', 'Mid result is updated');
                return $this->render('dashboard/Examination/update_mid_exam_records.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'record'  => $record
                ]);
            }else{
                $record->setFinal($request->request->get('final'));
                $record->setSestional($request->request->get('sestional'));
                $record->setMid($request->request->get('mid'));
                $record->setModifiedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($record);
                $em->flush();
                $this->addFlash('success', 'Mid result is updated');
                return $this->render('dashboard/Examination/update_final_exam_records.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'record'  => $record
                ]);
            }
        }
        if($record->getSestional() == null && $record->getFinal() == null){
            return $this->render('dashboard/Examination/update_mid_exam_records.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'record'  => $record
            ]);
        }else{
            return $this->render('dashboard/Examination/update_final_exam_records.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'record'  => $record
            ]);
        }

        dump($request);
        die();
    }
}