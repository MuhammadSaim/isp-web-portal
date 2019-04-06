<?php
/**
 * Created by PhpStorm.
 * User: sami
 * Date: 4/1/19
 * Time: 9:52 PM
 */

namespace App\Controller\Dashboard\Section;


use App\Entity\Departments;
use App\Entity\Sections;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SectionController
 * @package App\Controller\Dashboard\Section
 * @Security("is_granted(['ROLE_TEACHER', 'ROLE_STUDENT'])", statusCode=404)
 * @Route("/section", name="section_")
 */
class SectionController extends AbstractController
{

    /**
     * @Route("/all", name="all")
     */
    public function allSections()
    {
        return $this->render('dashboard/sections/all_sections.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'sections'    => $this->getDoctrine()->getRepository(Sections::class)->findAll()
        ]);
    }


}