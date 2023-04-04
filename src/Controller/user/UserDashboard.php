<?php

namespace App\Controller\user;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class UserDashboard extends AbstractController{

    public function __construct(
        private Security $security,
    ){}

    /*#[Route('/adminDashboard', name:'app_admindashboard')]
    public function index(){
        return $this->render('adminDashboard.html.twig',[
            'user'=>$this->security->getUser(),
        ]);
    }*/
}