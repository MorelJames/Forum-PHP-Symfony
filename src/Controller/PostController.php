<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use Monolog\DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;

class PostController extends AbstractController
{

    #[Route('/postController/{postId}', name:"post")]
    public function showPost(ManagerRegistry $doctrine, Request $request, Int $postId)
    {
        $post = $doctrine->getRepository(Post::class)->find($postId);
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {


            $entity = $doctrine->getManager();

            $user = $doctrine->getRepository(User::class)->find(1);

            $comment = $formComment->getData();
            $comment->setCreatedAt(new DateTimeImmutable(false));
            $comment->setPost($post);
            $comment->setValid(false);
            $comment->setUser($user);

            $entity->persist($comment);
            $entity->flush();

            $allComments = $doctrine->getRepository(Comment::class)->findBy(['post'=>$post]);

            return $this->render('/Post.html.twig', [
                'post' => $post,
                'formComment' => $formComment->createView(),
                'comments' => $allComments
            ]);
        }

        $allComments = $doctrine->getRepository(Comment::class)->findBy(['post'=>$post]);
        return $this->render('/Post.html.twig', [
            'post' => $post,
            'formComment' => $formComment->createView(),
            'comments' => $allComments
        ]);
    }
}
