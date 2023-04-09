<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;

class TestForAdminAccountController extends AbstractController{

    public function __construct(
        private Security $security,
    ){}

    //cette fonction est la uniquement pour la version de développement pour créer
    //le premier utilisateur avec le role ADMIN. à partir du moment ou au moins un
    // utilisateur admin est créer, il pourra attribuer ce role à d'autres utilisateurs
    #[Route('/routeTestForAdminAccount')]
    public function index(ManagerRegistry $doctrine){

        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_ADMIN']);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('app_allCategory');
    }

}