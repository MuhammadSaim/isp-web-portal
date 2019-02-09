<?php

namespace App\Controller\Dashboard;

use App\Entity\Departments;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')", statusCode=404)
 * @Route("/dashboard", name="dashboard_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
//        dump($this->getUser());
        return $this->render('dashboard/index.html.twig', [
            'departments' => $this->getDoctrine()->getRepository(Departments::class)->findAll()
        ]);
    }
}
