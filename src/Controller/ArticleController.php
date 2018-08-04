<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\{Article,Category,User};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class ArticleController extends Controller
{


    /**
     * @Route("/articles", name="allArticles",methods="GET")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([
            'is_published' => true
        ],['created_at' => 'DESC']);

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/articles/{id}",name="showArticle",requirements={"id"="\d+"})
     */
    public function show($id){
       
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if($article->getIsPublished() !== true){
            return  $this->redirectToRoute('allArticles');
        };
        return $this->render('article/show.html.twig',[

                'article' => $article
        ]);
        
    }

    /**
     * @Route("articles/create",name="addArticle",methods="GET")
     */
    public function create(){

        if($this->userIsNotAdmin()){
            return  $this->redirectToRoute('allArticles');
        };
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        
        return $this->render('article/create.html.twig',[
            'categories' => $categories

        ]);
    }


    /**
     * @Route("articles",name="stortArticle", methods="POST")
     */
    public function store(ValidatorInterface $validator,Session $session){
        if($this->userIsNotAdmin()){
            return  $this->redirectToRoute('allArticles');
        };
        $request = Request::createFromGlobals();
        $title = $request->request->get('title');
        $body = $request->request->get('body');
        $is_published = $request->request->get('is_published');

        $category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('category'));
        $user = $this->getUser();

        $manager = $this->getDoctrine()->getManager();

        $article = new Article;
        $article->setTitle($title);
        $article->setBody($body);
        $article->setCategory($category);
        $article->setUser($user);
        $article->setFeaturedPhoto('ayaga');
        $article->setCreatedAt(new \DateTime(date("Y-m-d H:i:s")));
        $article->setIsPublished($is_published);
        $errors = $validator->validate($article);

        if (count($errors) > 0) {
        $errors = (string) $errors;
          $session->getFlashBag()->add('errors',$errors);
          return $this->redirectToRoute('addArticle');
        
        }
    
        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('showArticle', [
            'id' => $article->getId()
        ]);
  

}

/**
     * @Route("articles/{id}/edit",requirements={"id"="\d+"})
*/
public function edit($id){
  
    if($this->userIsNotAdmin()){
        return  $this->redirectToRoute('allArticles');
    };
    
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
    $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
    
    return $this->render('article/edit.html.twig',[
        'categories' => $categories,
        'article' => $article

    ]);
}

/**
     * @Route("updateArticle/{id}",name="updateArticle",requirements={"id"="\d+"})
*/
public function update($id){
    if($this->userIsNotAdmin()){
        throw $this->createAccessDeniedException('You cannot access this page!');
    };
    
    $request = Request::createFromGlobals();
    $title = $request->request->get('title');
    $body = $request->request->get('body');
    $is_published = $request->request->get('is_published');



    $manager = $this->getDoctrine()->getManager();
    $article = $manager->getRepository(Article::class)->find($id);
    $category = $manager->getRepository(Category::class)->find($request->request->get('category'));

    $article->setTitle($title);
    $article->setBody($body);
    $article->setCategory($category);
    $article->setIsPublished($is_published);
    $manager->flush();

    return $this->redirectToRoute('showArticle', [
        'id' => $article->getId()
    ]);}



    /**
     * @Route("deleteArticle/{id}",name="deleteArticle",requirements={"id"="\d+"})
     */
    public function deleteArticle($id){
        if($this->userIsNotAdmin()){
            throw $this->createAccessDeniedException('You cannot access this page!');
        };
        $manager = $this->getDoctrine()->getManager();
        $article = $manager->getRepository(Article::class)->find($id);
        $manager->remove($article);
        $manager->flush();

        return  $this->redirectToRoute('allArticles');
    }



    public function userIsNotAdmin(){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getIsAdmin()  !== true){
             return  true;
        }
        
    }

}
