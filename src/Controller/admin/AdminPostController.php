<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;
use App\Form\PostType;
use Monolog\DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;

class AdminPostController extends AbstractController
{

    #[Route('/postAdmin')]
    public function base(ManagerRegistry $doctrine, Request $request)
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

            return $this->render('/Post.html.twig', [
                'post' => $post,
            ]);
        }

        return $this->render('/AdminPost.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
