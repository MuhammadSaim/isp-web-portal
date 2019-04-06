<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 4/1/19
 * Time: 1:02 PM
 */

namespace App\Controller\Dashboard\Semester;


use App\Entity\Departments;
use App\Entity\Semesters;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SemesterController
 * @package App\Controller\Dashboard\Semester
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
 * @Route("/semester", name="semester_")
 */
class SemesterController extends AbstractController
{

    /**
     * @Route("/all", name="all")
     */
    public function allSemester()
    {
        return $this->render('dashboard/semester/all_semesters.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'semesters'    => $this->getDoctrine()->getRepository(Semesters::class)->findAllWithAscOrder()
        ]);
    }


    /**
     * @param Request $request
     * @Route("/new", name="new")
     */
    public function addSemester(Request $request)
    {
        $semester = new Semesters();
        if($request->isMethod('POST')){
            $sem = $request->request->get('semester');
            $check = $this->getDoctrine()->getRepository(Semesters::class)->findBy([
                'semester' => $sem
            ]);
            if($check != null){
                $this->addFlash('danger', 'Semester is already exists.');
                return $this->render('dashboard/semester/new_semester.html.twig', [
                    'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
                ]);
            }
            $em = $this->getDoctrine()->getManager();
            $semester->setSemester($sem);
            $semester->setCreatedAt(new \DateTime());
            $semester->setModifiedAt(new \DateTime());
            $em->persist($semester);
            $em->flush();
            $this->addFlash('success', 'Semester is added successfully.');
            return $this->render('dashboard/semester/new_semester.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
            ]);
        }
        return $this->render('dashboard/semester/new_semester.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
        ]);
    }


    /**
     * @param $id
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id)
    {
        $semester = $this->getDoctrine()->getRepository(Semesters::class)->find($id);
        if($semester == null){
            throw new NotFoundHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($semester);
        $em->flush();
        $this->addFlash('success', 'Semester is deleted successfully.');
        return $this->redirectToRoute('semester_all');
    }


}