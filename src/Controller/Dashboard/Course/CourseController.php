<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 2/16/19
 * Time: 12:46 PM
 */

namespace App\Controller\Dashboard\Course;


use App\Entity\Courses;
use App\Entity\Departments;
use App\Form\AddCourseType;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CourseController
 * @Route("/course", name="course_")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 */
class CourseController extends AbstractController
{
    private $helperFunc;

    public function __construct(HelperFunction $helperFunc)
    {
        $this->helperFunc = $helperFunc;
    }

    /**
     * @Route("/add", name="add")
     */
    public function addCourse(Request $request)
    {
        $course = new Courses();
        $form = $this->createForm(AddCourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $courseName = $request->request->get('add_course')['course'];
            $slug = $this->helperFunc->slugify($courseName);
            $courseCode = $request->request->get('add_course')['courseCode'];
            $courseSlug = $this->getDoctrine()->getRepository(Courses::class)->findBy(['slug' => $slug]);
            $courseCode = $this->getDoctrine()->getRepository(Courses::class)->findBy(['courseCode' => $courseCode]);
            if($courseSlug){
                $slug = $slug."-".substr(md5(rand(0, 150000)), 0, 5);
            }
            if($courseCode){
                $this->addFlash('danger', 'This '.$courseCode.' is already exists.');
                return $this->render('dashboard/course/add_course.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                    'form'        => $form->createView()
                ]);
            }
            $course->setSlug($slug);
            $course->setCreatedAt(new \DateTime());
            $course->setModifiedAt(new \DateTime());

            //persist data to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $this->addFlash('success', 'Course '.$courseName.' is added successfully.');
        }

       return $this->render('dashboard/course/add_course.html.twig',[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'form'        => $form->createView()
        ]);
    }

    /**
     * @Route("/all", name="all")
     */
    public function allCourses()
    {
        return $this->render('dashboard/course/all_courses.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'courses'     => $this->getDoctrine()->getRepository(Courses::class)->findAllWithAscOrder()
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="delete")
     */
    public function removeCourse($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $findCourse = $em->getRepository(Courses::class)->findOneBy(['slug'=>$slug]);
//        dump($findCourse->getCourse());
//        die();
        if(!$findCourse){
            throw $this->createNotFoundException();
        }
        $em->remove($findCourse);
        $em->flush();
        $this->addFlash('danger', 'Course '.$findCourse->getCourse().' is deleted.');
        return $this->redirectToRoute('course_all');

    }

    /**
     * @Route("/update/{slug}", name="update")
     */
    public function updateCourse(Request $request, $slug)
    {
        $course = $this->getDoctrine()->getRepository(Courses::class)->find(
            $this->getDoctrine()->getRepository(Courses::class)->findBy([
                'slug' => $slug
            ])[0]->getId()
        );
        $form = $this->createForm(AddCourseType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
//            $courseCode = $request->request->get('add_course')['courseCode'];
//            $courseCode = $this->getDoctrine()->getRepository(Courses::class)->findCode($course, $courseCode);
//            if($courseCode){
//                $this->addFlash('danger', 'This '.$courseCode.' is already exists.');
//                return $this->render('dashboard/course/update_course.html.twig', [
//                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
//                    'form'        => $form->createView()
//                ]);
//            }
            $course->setModifiedAt(new \DateTime());
            //persist data to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();
            $this->addFlash('success', 'Course is updated successfully.');
        }
        return $this->render('dashboard/course/update_course.html.twig',[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'form'        => $form->createView()
        ]);
    }

}