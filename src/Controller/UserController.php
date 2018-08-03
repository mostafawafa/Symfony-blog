<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
      
    }

    /**
     * @Route("/users/{id}", name="users.show")
     */
    public function show($id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('user/show.html.twig',[
            'user' => $user
        ]);

    }

    /**
     * @Route("/register",name="registerForm",methods="GET")
     */
    public function getRegisterFrom(){

          return  $this->render('user/register.html.twig');

    }

      /**
     * @Route("/register",name="register",methods="POST")
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder){

        $user =new User;
        $user->setName($request->request->get('name'));
        $user->setUsername($request->request->get('username'));
        $user->setEmail($request->request->get('email'));
        $user->setPhone($request->request->get('phone'));
        $user->setIsAdmin(false);
        $user->setIsActive(false);
        $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));

        $manager  = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return new Response('done');

    }
}
