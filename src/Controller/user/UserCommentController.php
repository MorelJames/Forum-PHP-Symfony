<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserCommentController extends AbstractController{
    
    #[Route('/commentUser/{name}', methods: ['GET'])]
    public function base($name){

        return $this->render('/UserComment.html.twig',[
            'name'=>$name,
        ]);
    }
}