<?php

namespace App\Controller\Dashboard\Announcement;


use App\Entity\Announcements;
use App\Entity\Courses;
use App\Entity\Departments;
use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\Semesters;
use App\Entity\StudentDetails;
use App\Entity\TeacherCourseMapping;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnncouncementController
 * @package App\Controller\Dashboard\Announcement
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
 * @Route("/announcement", name="announcement_")
 */
class AnncouncementController extends AbstractController
{

    private $helperFunction;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunction = $helperFunction;
    }

    /**
     * @Route("/", name="all")
     */
    public function announcements()
    {
        if($this->isGranted('ROLE_TEACHER')){
            $announcements = $this->getDoctrine()->getRepository(Announcements::class)->findBy([
                'teacher' => $this->getUser()
            ]);
        }else{
            $announcements = $this->getDoctrine()->getRepository(Announcements::class)->findBy([
                'department' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getDepartment(),
                'program' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getProgram(),
                'semester' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getSemester(),
                'section' => $this->getDoctrine()->getRepository(StudentDetails::class)->findBy(['user' => $this->getUser()])[0]->getSection()
            ]);
        }
        return $this->render('dashboard/announcements/all_announcements.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'announcements' => $announcements
        ]);
    }


    /**
     * @param Request $request
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     * @Route("/new", name="add_new")
     */
    public function addAnnouncement(Request $request)
    {
        $announcement = new Announcements();
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
        if($request->isMethod('POST')){
            $department = $this->getDoctrine()->getRepository(Departments::class)->find($request->request->get('department'));
            $program = $this->getDoctrine()->getRepository(Programs::class)->find($request->request->get('program'));
            $course = $this->getDoctrine()->getRepository(Courses::class)->find($request->request->get('course'));
            $semester = $this->getDoctrine()->getRepository(Semesters::class)->find($request->request->get('semester'));
            $section = $this->getDoctrine()->getRepository(Sections::class)->find($request->request->get('section'));
            $title = $request->request->get('title');
            $type = $request->request->get('type');
            $description = $request->request->get('description');
            $slug  = $this->helperFunction->slugify($title);
            $checkSlug = $this->getDoctrine()->getRepository(Announcements::class)->findBy([
                'announcementSlug' => $slug
            ]);
            if($checkSlug != null){
                $slug = $slug.'-'.$this->helperFunction->getUiqueName();
            }
            $announcement->setTeacher($this->getUser());
            $announcement->setDepartment($department);
            $announcement->setProgram($program);
            $announcement->setCourse($course);
            $announcement->setSemester($semester);
            $announcement->setSection($section);
            $announcement->setAnnouncementTitle($title);
            $announcement->setAnnouncementType($type);
            $announcement->setAnnouncementSlug($slug);
            $announcement->setAnnouncementDescription($description);
            $announcement->setCreatedAt(new \DateTime());
            $announcement->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($announcement);
            $em->flush();
            $this->addFlash('success', 'Announcement is added successfully');
            return $this->render('dashboard/announcements/add_announcement.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'data' => $data
            ]);

        }
        return $this->render('dashboard/announcements/add_announcement.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'data' => $data
        ]);
    }


    /**
     * @Security("is_granted('ROLE_TEACHER')", statusCode=404)
     * @Route("/delete/{announcementSlug}", name="delete")
     */
    public function deleteAnnouncements(Request $request, $announcementSlug)
    {
        $announcement = $this->getDoctrine()
            ->getRepository(Announcements::class)->findBy([
            'announcementSlug' => $announcementSlug
        ]);
        if($announcement == null){
            throw new NotFoundHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($announcement[0]);
        $em->flush();
        return $this->redirectToRoute('announcement_all');
    }
}