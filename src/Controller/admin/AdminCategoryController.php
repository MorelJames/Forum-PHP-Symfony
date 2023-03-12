<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminCategoryController extends AbstractController{

    #[Route('/categoryAdmin/{category}', methods: ['GET'])]
    public function base($category){

        return $this->render('/AdminCategory.html.twig',[
            'category'=> $category,
        ]);
    }
}