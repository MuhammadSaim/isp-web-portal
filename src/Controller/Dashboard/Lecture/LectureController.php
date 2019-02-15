<?php

namespace App\Controller\Dashboard\Lecture;


use App\Entity\Departments;
use App\Entity\Lectures;
use App\Form\AddLectureType;
use App\Form\AddTeacherType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LectureController
 * @package App\Controller\Dashboard\Lecture
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/lectures", name="lectures_")
 */
class LectureController extends AbstractController
{

    /**
     * @Route("/add", name="add")
     */
    public function addLecture(Request $request)
    {

        $lectures = new Lectures();
        $form = $this->createForm(AddLectureType::class, null);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dump($form->getData());
            die();
        }
        return $this->render('dashboard/lecture/add_lecture.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'form' => $form->createView()
        ]);
    }

}