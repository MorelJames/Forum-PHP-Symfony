<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserPostController extends AbstractController{
    
    #[Route('/postUser/{name}', methods: ['GET'])]
    public function base($name) {

        return $this->render('/UserPost.html.twig',[
            'name'=> $name
        ]);
    }
}