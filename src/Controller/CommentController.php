<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request,Response};
use App\Entity\{Comment,Article};

class CommentController extends Controller
{
    /**
     * @Route("comments", name="addComment" ,methods="POST")
     */
    public function store()
    {
        $request = Request::createFromGlobals();
        $article = $this->getDoctrine()->getRepository(Article::class)->find($request->request->get('article'));
        $manager = $this->getDoctrine()->getManager();

        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setBody($request->request->get('comment'));
        
        $manager->persist($comment);
        $manager->flush();

        return new Response('done');
    }
}
