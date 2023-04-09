<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class CommentController extends AbstractController
{
    public function __construct(private Security $security){}

    #[Route('/removeComment/{commentId}', name: "rmComment")]
    public function removeComment(ManagerRegistry $doctrine, Int $commentId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

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
