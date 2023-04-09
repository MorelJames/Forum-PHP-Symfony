<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;

class AdminAllPostController extends AbstractController
{

    #[Route('/allPostAdmin', name: 'app_allPostAdmin')]
    public function base(ManagerRegistry $doctrine)
    {
        $allPost = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('/AllPost.html.twig', [
            'allPost' => $allPost,
        ]);
    }

    #[Route('/signalPost/{postId}', name: 'app_signal_post')]
    public function signalPost(Request $request, ManagerRegistry $doctrine, Int $postId) : Response
    {
        $post = $doctrine->getRepository(Post::class)->find($postId);
        $post->setSignaled(true);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('app_allPostAdmin');
    }
}
