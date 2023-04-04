<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;

class AdminAllPostController extends AbstractController
{

    #[Route('/allPostAdmin', name: 'app_allPostAdmin')]
    public function base(ManagerRegistry $doctrine)
    {
        // autorise uniquement les administrateurs, sinon erreur
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $allPost = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('/AllPost.html.twig', [
            'allPost' => $allPost,
        ]);
    }
}
