<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="article")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/articles/{id}")
     */
    public function show($id){

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        
        return $this->render('article/show.html.twig',[

                'article' => $article
        ]);
        
    }
}
