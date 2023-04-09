<?php

namespace App\Controller\admin;

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
use Symfony\Component\Security\Core\Security;

class AdminPostController extends AbstractController
{
    public function __construct(private Security $security){}

    #[Route('/postAdmin', name: 'app_postAdmin')]
    public function base(ManagerRegistry $doctrine, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_BLOG', null, 'User tried to access a page without having ROLE_BLOG');

        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        if(!$user->isVerified()) {
            return $this->render('requireValidation.html.twig');
        }

        $post = new Post();
        

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment = $formComment->getData();
        }

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

    #[Route('/signalPost/{postId}', name: 'app_signal_post')]
    public function signalPost(Request $request, ManagerRegistry $doctrine, Int $postId) : Response
    {
        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);

        if(!$user->isVerified()) {
            return $this->render('requireValidation.html.twig');
        }
        
        $post = $doctrine->getRepository(Post::class)->find($postId);
        $post->setSignaled(true);
        $post->setReportedAt(new DateTimeImmutable(false));
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('post',[
            'postId' => $postId]);
    }
}
