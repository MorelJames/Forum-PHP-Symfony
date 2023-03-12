<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserCategoryController extends AbstractController{
    
    #[Route('/categoryUser/{category}', methods: ['GET'])]
    public function base($category){

        return $this->render('/UserCategory.html.twig',[
            'category'=> $category,
        ]);
    }
}