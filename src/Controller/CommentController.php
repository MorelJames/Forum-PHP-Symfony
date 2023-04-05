<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CommentController extends AbstractController
{

    #[Route('/removeComment/{commentId}', name: "rmComment")]
    public function removeComment(ManagerRegistry $doctrine, Int $commentId)
    {
        $comment = $doctrine->getRepository(Comment::class)->find($commentId);
        $postId = $comment->getPost()->getId();
        $entity = $doctrine->getManager();
        $entity->remove($comment);
        $entity->flush();

        return $this->redirectToRoute('post',[
            'postId' => $postId,
        ]);
    }
}
