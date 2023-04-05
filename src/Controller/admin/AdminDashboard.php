<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class AdminDashboard extends AbstractController{

    public function __construct(
        private Security $security,
    ){}

    #[Route('/adminDashboard', name:'app_admindashboard')]
    public function index(ManagerRegistry $doctrine){
        $this->setAdmin($doctrine);
        return $this->render('adminDashboard.html.twig',[
            'user'=>$this->security->getUser(),
        ]);
    }

    public function setAdmin(ManagerRegistry $doctrine)
    {
        # TODO : gérer le fait que l'utilisateur de la session n'est pas mis à jour
        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_ADMIN']);
        $entity = $doctrine->getManager();
        $entity->flush();
    }
}