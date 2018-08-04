<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Category;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;



class CategoryController extends Controller
{
    /**
     * @Route("/categories/{id}", name="category",requirements={"id"="\d+"})
     */
    
     public function show($id){

        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $criteria = Criteria::create()
             ->where(Criteria::expr()->eq("is_published", true));

        $articles = $category->getArticles()->matching($criteria);
        
      
         return $this->render('category/show.html.twig',[
             'category' => $category,
             'categories' => $categories,
             'articles' => $articles
         ]);
         

     }


     /**
      * @Route("/categories/create",name="createCategory",methods="GET")
      */
      public function create(){
        if($this->userIsNotAdmin()){
            return $this->redirectToRoute('allArticles');
 
         }

        return $this->render('category/create.html.twig');

      }

      /**
      * @Route("/categories",name="storeCategory",methods="Post")
      */
      public function store(Request $request,ValidatorInterface $validator,Session $session){
        if($this->userIsNotAdmin()){
           return $this->redirectToRoute('allArticles');

        }
           $name =  $request->request->get('name');
           $description =  $request->request->get('description');

           $category = new Category;
           $category->setName($name);
           $category->setDescription($description);

          $errors =  $validator->validate($category);
          if (count($errors) > 0) {
            $errors = (string) $errors;
              $session->getFlashBag()->add('errors',$errors);
              return $this->redirectToRoute('showArticle',[
                  'id' => $article->id  

              ]);
            
            }



           $manager = $this->getDoctrine()->getManager();
           $manager->persist($category);
           $manager->flush();

        
            return $this->redirectToRoute('allArticles');
           



      }


      public function userIsNotAdmin(){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getIsAdmin()  !== true){
             return  true;
        }
        
    }
}
