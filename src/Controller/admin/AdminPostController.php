<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminPostController extends AbstractController{
    
    #[Route('/postAdmin/{name}', methods: ['GET'])]
    public function base($name) {

        return $this->render('/AdminPost.html.twig',[
            'name'=> $name
        ]);
    }
}