<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminPostController extends AbstractController{
    
    #[Route('/postAdmin', methods: ['GET'])]
    public function base() : Response{

        return new Response('admin post');
    }
}