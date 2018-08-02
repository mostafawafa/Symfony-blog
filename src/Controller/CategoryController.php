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
class CategoryController extends Controller
{
    /**
     * @Route("/categories/{id}", name="category")
     */
    
     public function show($id){

         $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

         return $this->render('category/show.html.twig',[
             'category' => $category,
             'categories' => $categories
         ]);
         

     }
}
