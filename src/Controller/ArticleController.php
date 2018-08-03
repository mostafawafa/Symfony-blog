<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\{Article,Category,User};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="allArticles",methods="GET")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([
            'is_published' => true
        ]);
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
        
        return $this->render('article/show.html.twig',[

                'article' => $article
        ]);
        
    }

    /**
     * @Route("articles/create")
     */
    public function create(){

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        
        return $this->render('article/create.html.twig',[
            'categories' => $categories

        ]);
    }


    /**
     * @Route("articles",name="addArticle", methods="POST")
     */
    public function store(){

        $request = Request::createFromGlobals();
        $title = $request->request->get('title');
        $body = $request->request->get('body');
        $is_published = $request->request->get('is_published');

        $category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('category'));
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);

        $manager = $this->getDoctrine()->getManager();

        $article = new Article;
        $article->setTitle($title);
        $article->setBody($body);
        $article->setCategory($category);
        $article->setUser($user);
        $article->setFeaturedPhoto('ayaga');
        $article->setCreatedAt(new \DateTime(date("Y-m-d H:i:s")));
        $article->setIsPublished($is_published);

        $manager->persist($article);
        $manager->flush();

        return new Response('done');

}

/**
     * @Route("articles/{id}/edit")
*/
public function edit($id){
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    if($this->getUser()->getIsAdmin()  !== true){
         return  $this->redirectToRoute('allArticles');
    }
    
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
    $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
    
    return $this->render('article/edit.html.twig',[
        'categories' => $categories,
        'article' => $article

    ]);
}

/**
     * @Route("updateArticle/{id}",name="updateArticle")
*/
public function update($id){

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
     * @Route("deleteArticle/{id}",name="deleteArticle")
     */
    public function deleteArticle($id){
        $manager = $this->getDoctrine()->getManager();
        $article = $manager->getRepository(Article::class)->find($id);
        $manager->remove($article);
        $manager->flush();

        return  $this->redirectToRoute('allArticles');
    }




}
