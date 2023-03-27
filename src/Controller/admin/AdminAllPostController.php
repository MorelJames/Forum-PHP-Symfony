<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;

class AdminAllPostController extends AbstractController
{

    #[Route('/allPostAdmin')]
    public function base(ManagerRegistry $doctrine)
    {

        $allPost = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('/AllPost.html.twig', [
            'allPost' => $allPost,
        ]);
    }
}
