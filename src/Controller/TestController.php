<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
       
        die(print_r( $this->getDoctrine()->getRepository(Article::class)->find(1)->getBody() ));
        /*
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
        */
        
    }

    
    /**
     * @Route("/admin")
     */
    public function admin(){
        return new Response('<html><body> hello world! </body> </html>');
    }
}
