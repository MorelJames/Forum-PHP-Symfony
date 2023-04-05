<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use Monolog\DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
{

    #[Route('/postController/{postId}', name: "post")]
    public function showPost(ManagerRegistry $doctrine,Security $security, Request $request, Int $postId)
    {
        $post = $doctrine->getRepository(Post::class)->find($postId);
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);
        $user = $security->getUser();

        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $entity = $doctrine->getManager();
            $comment = $formComment->getData();
            $comment->setCreatedAt(new DateTimeImmutable(false));
            $comment->setPost($post);
            $comment->setValid(false);
            $comment->setUser($user);

            $entity->persist($comment);
            $entity->flush();

            return $this->render('Post/Post.html.twig', [
                'post' => $post,
                'formComment' => $formComment->createView(),
            ]);
        }

        return $this->render('Post/Post.html.twig', [
            'post' => $post,
            'formComment' => $formComment->createView(),
        ]);
    }

    #[Route('/removePost/{postId}', name: "rmPost")]
    public function removePost(ManagerRegistry $doctrine, Int $postId)
    {
        $post = $doctrine->getRepository(Post::class)->find($postId);
        if ($post != null) {
            $entity = $doctrine->getManager();
            $entity->remove($post);
            $entity->flush();
        }

        return $this->redirectToRoute('app_allPostAdmin');
    }
}
