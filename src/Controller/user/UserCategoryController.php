<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserCategoryController extends AbstractController{
    
    #[Route('/categoryUser', methods: ['GET'])]
    public function base() : Response{

        return new Response('user categ');
    }
}