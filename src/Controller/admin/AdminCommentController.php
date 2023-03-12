<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AbstractController{
    
    #[Route('/commentAdmin/{name}', methods: ['GET'])]
    public function base($name){

        return $this->render('/AdminComment.html.twig',[
            'name'=>$name,
        ]);
    }
}