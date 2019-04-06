<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityController extends AbstractController
{

    private $swiftMailer;
    private $passwordEncoder;

    public function __construct(\Swift_Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->swiftMailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->isLogedIn())
            return $this->redirectToRoute('dashboard_home');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        if(!$this->isLogedIn())
            return $this->redirectToRoute('app_login');
    }


    /**
     * @param Request $request
     * @Route("/forgot", name="passowrd_forgot")
     */
    public function passwordRest(Request $request)
    {
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $user = $this->getDoctrine()->getRepository(User::class)->findBy([
                'email' => $email
            ]);
            if(count($user) == 0){
                $this->addFlash('danger', 'Ooops! email is not found.');
                return $this->render('security/forgot_password.html.twig');
            }else{
                $remember_token = hash('sha256', uniqid(), false);
                $user[0]->setRememberToken($remember_token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user[0]);
                $em->flush();
                $data = array(
                    'email' => $user[0]->getEmail(),
                    'token' => $remember_token
                );
                if($this->sendResetEmail($data)){
                    $this->addFlash('success', 'Reset link send successfully on your email.');
                    return $this->render('security/forgot_password.html.twig');
                }else{
                    $this->addFlash('danger', 'Something went wrong please try again.');
                    return $this->render('security/forgot_password.html.twig');
                }
            }
        }
        return $this->render('security/forgot_password.html.twig');
    }


    /**
     * @param Request $request
     * @param $token
     * @Route("/verify/{token}", name="verify_reset_password")
     */
    public function verifyResetPassword(Request $request, $token)
    {
        $verify = $this->getDoctrine()->getRepository(User::class)->findBy([
            'rememberToken' => $token
        ]);
        if($request->isMethod('POST')){
            $verify = $this->getDoctrine()->getRepository(User::class)->findBy([
                'rememberToken' => $token
            ]);
            if(count($verify) == 0){
                throw new NotFoundHttpException();
            }else{
                $password = $request->request->get('password');
                $confirm_password = $request->request->get('confirmPassword');
                if($password === $confirm_password){
                    $hashPass = $this->passwordEncoder->encodePassword($verify[0], $password);
                    $verify[0]->setPassword($hashPass);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($verify[0]);
                    $em->flush();
                    $this->addFlash('success', 'Password is updated successfully.');
                    return $this->render('security/reset_password.html.twig');
                }else{
                    $this->addFlash('danger', 'Passwords are not same.');
                    return $this->render('security/reset_password.html.twig');
                }
            }
        }
        if(count($verify) == 0){
            throw new NotFoundHttpException();
        }else{
            return $this->render('security/reset_password.html.twig');
        }

    }

    public function sendResetEmail($data){
        $message = (new \Swift_Message('ISP Portal forgot password'))
            ->setFrom('noreply@portal.isp.edu.pk')
            ->setTo($data['email'])
            ->setBody(
                $this->renderView(
                    'emails/rest_password.html.twig',
                    $data
                ), 'text/html'
            );

        if($this->swiftMailer->send($message))
            return true;
        else
            return false;

    }

    public function isLogedIn()
    {
        if($this->isGranted("IS_AUTHENTICATED_FULLY"))
            return true;
        return false;
    }
}
