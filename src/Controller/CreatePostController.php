<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use Monolog\DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;

class CreatePostController extends AbstractController
{
    #[Route('/createPost')]
    public function createPost(ManagerRegistry $doctrine, Request $request) : Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $post->setPublishedAt(new DateTimeImmutable(false));

            $post->setSlug('/' . $post->getTitle());

            $entity = $doctrine->getManager();

            $entity->persist($post);
            $entity->flush();

            return $this->redirectToRoute('post',[
                'postId' => $post->getId(),
            ]);
        }

        return $this->render('/AdminPost.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
