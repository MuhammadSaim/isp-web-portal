<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 4/1/19
 * Time: 11:19 AM
 */

namespace App\Controller\Dashboard\Program;


use App\Entity\Departments;
use App\Entity\Programs;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ProgramControlle
 * @package App\Controller\Dashboard\Program
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
 * @Route("/program", name="program_")
 */
class ProgramControlle extends AbstractController
{

    private $helperFunction;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunction = $helperFunction;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/new", name="new")
     */
    public function addNew(Request $request)
    {
        $program = new Programs();
        if($request->isMethod('POST')){
            $department = $request->request->get('department');
            $program_title = $request->request->get('program');
            $slug = $this->helperFunction->slugify($program_title);
            $checkSlug = $this->getDoctrine()->getRepository(Programs::class)->findBy([
                'slug' => $slug
            ]);
            if($checkSlug != null){
                $slug = $slug.'-'.$this->helperFunction->getUiqueName();
            }
            $program->setProgram($program_title);
            $program->setdepartment($this->getDoctrine()->getRepository(Departments::class)->find($department));
            $program->setSlug($slug);
            $program->setCreatedAt(new \DateTime());
            $program->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($program);
            $em->flush();
            $this->addFlash('success', 'Program is added successfully.');
            return $this->render('dashboard/program/new_program.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
            ]);
        }
        return $this->render('dashboard/program/new_program.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
        ]);
    }


    /**
     * @Route("/all", name="all")
     */
    public function allPrograms()
    {
        return $this->render('dashboard/program/all_programs.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'programs'    => $this->getDoctrine()->getRepository(Programs::class)->findAllWithAscOrder()
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="delete")
     */
    public function deleteProgram($slug)
    {
        $program = $this->getDoctrine()->getRepository(Programs::class)->findBy([
            'slug' => $slug
        ]);
        if($program == null){
            throw new NotFoundHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($program[0]);
        $em->flush();
        $this->addFlash('danger', 'Program '.$program[0]->getProgram().' is deleted.');
        return $this->redirectToRoute("program_all");
    }


    /**
     * @param Request $request
     * @param $slug
     * @Route("/update/{slug}", name="update")
     */
    public function updateProgram(Request $request, $slug)
    {
        $program = $this->getDoctrine()->getRepository(Programs::class)->findBy([
           'slug' => $slug
        ]);
        if($program == null){
            throw new NotFoundHttpException();
        }
        if($request->isMethod('POST')){
            $department = $request->request->get('department');
            $program_title = $request->request->get('program');
            $program[0]->setProgram($program_title);
            $program[0]->setDepartment($this->getDoctrine()->getRepository(Departments::class)->find($department));
            $program[0]->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($program[0]);
            $em->flush();
            $this->addFlash('success', 'Program is updated successfully');
            return $this->render('dashboard/program/update_programs.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
                'program'    => $program[0]
            ]);
        }
        return $this->render('dashboard/program/update_programs.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'program'    => $program[0]
        ]);
    }


}