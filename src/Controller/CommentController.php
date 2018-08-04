<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request,Response};
use App\Entity\{Comment,Article};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class CommentController extends Controller
{
    /**
     * @Route("comments", name="addComment" ,methods="POST")
     */
    public function store(Request $request,ValidatorInterface $validator,Session $session)
    {
        $manager = $this->getDoctrine()->getManager();
        $article = $manager->getRepository(Article::class)->find($request->request->get('article'));
     
        if( ! $article->getIsPublished()){
            throw $this->createAccessDeniedException('You cannot access this page!');
        }
        

        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setBody($request->request->get('comment'));
        if($this->getUser()){
             $comment->setUser($this->getUser());
        }

        $errors = $validator->validate($comment);
        if (count($errors) > 0) {
            $errors = (string) $errors;
              $session->getFlashBag()->add('errors',$errors);
              return $this->redirectToRoute('showArticle',[
                  'id' => $article->id  

              ]);
            
            }

      
        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('showArticle', [
            'id' => $article->getId()
        ]);
      }
}
