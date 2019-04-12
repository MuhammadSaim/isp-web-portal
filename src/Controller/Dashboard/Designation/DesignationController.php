<?php


namespace App\Controller\Dashboard\Designation;

use App\Entity\Departments;
use App\Entity\Designations;
use App\Utils\HelperFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DesignationController
 * @package App\Controller\Dashboard\Designation
 * @Security("is_granted('ROLE_ADMIN')", statusCode=404)
 * @Route("/designation", name="designation_")
 */
class DesignationController extends AbstractController
{
    private $helperFunction;

    public function __construct(HelperFunction $helperFunction)
    {
        $this->helperFunction = $helperFunction;
    }

    /**
     * @param Request $request
     * @Route("/all", name="all")
     */
    public function allDesignations(Request $request)
    {
        return $this->render('dashboard/designation/all_designation.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            'designations' => $this->getDoctrine()->getRepository(Designations::class)->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/add", name="add")
     */
    public function addDesignation(Request $request)
    {
        if($request->isMethod('POST')){
            $designationname = $request->request->get('designation');
            $designation = new Designations();
            $designation->setDesignation($designationname);
            $slug = $this->helperFunction->slugify($designationname);
            $check = $this->getDoctrine()->getRepository(Designations::class)->findOneBy([
                'slug' => $slug
            ]);
            if($check != null){
                $slug = $slug.'-'.$this->helperFunction->getUiqueName();
            }
            $designation->setSlug($slug);
            $designation->setCreatedAt(new \DateTime());
            $designation->setModifiedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($designation);
            $em->flush();
            $this->addFlash('success', 'Designation is added successfully.');
            return $this->render('dashboard/designation/add_designation.html.twig', [
                'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
            ]);

        }
        return $this->render('dashboard/designation/add_designation.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll(),
        ]);
    }


}