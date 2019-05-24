<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 2/8/19
 * Time: 11:18 PM
 */

namespace App\Controller\Dashboard\Department;


use App\Entity\Departments;
use App\Entity\Sessions;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentController
 * @package App\Controller\Dashboard\Department
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/department", name="department_")
 */
class DepartmentController extends AbstractController
{

    private $helperFunctions;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunctions = $helperFunction;
    }


    /**
     * @Route("/staff/{slug}", name="teacher")
     */
    public function department_staff_details($slug)
    {
        $department_details = $this->getDoctrine()->getRepository(Departments::class)->findOneBy(['slug' => $slug]);
        if($department_details == null){
            throw $this->createNotFoundException();
        }
        return $this->render(
            "dashboard/department/department_staff_details.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'departmentDetails' => $department_details,
            'active_class' => 'teacher'
        ]);
    }

    /**
     * @Route("/students/{slug}", name="student")
     */
    public function department_student_details($slug)
    {
        $department_details = $this->getDoctrine()->getRepository(Departments::class)->findOneBy(['slug' => $slug]);
        dump($department_details);
        if($department_details == null){
            throw $this->createNotFoundException();
        }
        return $this->render(
            "dashboard/department/department_student_details.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'departmentDetails' => $department_details,
            'active_class' => 'student'
        ]);
    }

    /**
     * @Route("/courses/{slug}", name="courses")
     */
    public function department_courses_details($slug)
    {
        $department_details = $this->getDoctrine()->getRepository(Departments::class)->findOneBy(['slug' => $slug]);
        dump($department_details);
        if($department_details == null){
            throw $this->createNotFoundException();
        }
        return $this->render(
            "dashboard/department/department_programs_details.html.twig",[
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'departmentDetails' => $department_details,
            'active_class' => 'programs'
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')", statusCode=404)
     * @Route("/add", name="add")
     */
    public function addDepartment(Request $request)
    {
        $department = new Departments();
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        if($request->isMethod('POST')){
            $dep = $request->request->get('department');
            $checkdep = $this->getDoctrine()->getRepository(Departments::class)->findOneBy([
                'department' => $dep
            ]);
            if($checkdep != null){
                $this->addFlash('danger', 'Department is already added.');
                return $this->render('dashboard/department/add_department.html.twig', [
                    'departments' => $departments
                ]);
            }
            $slug = $this->helperFunctions->slugify($dep);
            $checkSlug = $this->getDoctrine()->getRepository(Departments::class)->findOneBy([
                'slug' => $slug
            ]);
            if($checkSlug != null){
                $slug = $slug.'-'.$this->helperFunctions->getUiqueName();
            }
            $department->setDepartment($dep);
            $department->setSlug($slug);
            $department->setCreatedAt(new \DateTime());
            $department->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();
            $this->addFlash('success', 'Department is added successfully.');
            return $this->render('dashboard/department/add_department.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
            ]);

        }
        return $this->render('dashboard/department/add_department.html.twig', [
            'departments' => $departments
        ]);
    }


    /**
     * @Security("is_granted('ROLE_ADMIN')", statusCode=404)
     * @Route("/add/session", name="add_session")
     */
    public function addSession(Request $request)
    {
        $session = new Sessions();
        $departments = $this->getDoctrine()->getRepository(Departments::class)->findAll();
        if($request->isMethod('POST')){
            $ses = $request->request->get('session');
            $checkses = $this->getDoctrine()->getRepository(Sessions::class)->findOneBy([
                'session' => $ses
            ]);
            if($checkses != null){
                $this->addFlash('danger', 'Session is already added.');
                return $this->render('dashboard/session/add_session.html.twig', [
                    'departments' => $departments
                ]);
            }
            $slug = $this->helperFunctions->slugify($ses);
            $checkSlug = $this->getDoctrine()->getRepository(Sessions::class)->findOneBy([
                'slug' => $slug
            ]);
            if($checkSlug != null){
                $slug = $slug.'-'.$this->helperFunctions->getUiqueName();
            }
            $session->setSession($ses);
            $session->setSlug($slug);
            $session->setCreatedAt(new \DateTime());
            $session->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            $this->addFlash('success', 'Session is added successfully.');
            return $this->render('dashboard/session/add_session.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
            ]);

        }
        return $this->render('dashboard/session/add_session.html.twig', [
            'departments' => $departments
        ]);
    }

}