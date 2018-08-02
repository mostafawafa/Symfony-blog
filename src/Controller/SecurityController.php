<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $auth){
        $error = $auth->getLastAuthenticationError();

        $lastName = $auth->getLastUsername();


        return $this->render('security/login.html.twig',[
            'lastName' => $lastName,
            'error' => $error

        ]);
    }

}
