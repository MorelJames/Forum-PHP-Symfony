<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\CommentType;
use Monolog\DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
{
    public function __construct(private Security $security){}

    #[Route('/postController/{postId}', name: "post")]
    public function showPost(ManagerRegistry $doctrine,Security $security, Request $request, Int $postId)
    {
        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        if(!$user->isVerified()) {
            return $this->render('requireValidation.html.twig');
        }

        $post = $doctrine->getRepository(Post::class)->find($postId);
        $comment = new Comment();

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);
        $user = $security->getUser();

        $session = $request->getSession();
        $page = '/postController/'.$post->getId();
        if (!$session->has('pages') || !in_array($page,$session->get('pages'))) {
            $post->incrementView();
            $pages = $session->get('pages');
            $pages[] = $page;
            $session->set('pages', $pages);
            $entity = $doctrine->getManager();
            $entity->flush();
        }

        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $entity = $doctrine->getManager();
            $comment = $formComment->getData();
            $comment->setCreatedAt(new DateTimeImmutable(false));
            $comment->setPost($post);
            $comment->setValid(false);
            $comment->setUser($user);

            $entity->persist($comment);
            $entity->flush();

            return $this->redirectToRoute('post',[
                'postId' => $post->getId(),
            ]);
        }

        return $this->render('Post/Post.html.twig', [
            'post' => $post,
            'formComment' => $formComment->createView(),
        ]);
    }

    #[Route('/createPost', name: "createPost")]
    public function createPost(ManagerRegistry $doctrine, Request $request, Security $security)
    {
        $this->denyAccessUnlessGranted('ROLE_BLOG', null, 'User tried to access a page without having ROLE_BLOG');

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        $user = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $post->setPublishedAt(new DateTimeImmutable(false));

            $post->setSlug('/' . $post->getTitle());

            $post->setUser($user);

            $entity = $doctrine->getManager();

            $entity->persist($post);
            $entity->flush();

            return $this->redirectToRoute('post',[
                'postId' => $post->getId(),
            ]);
        }

        return $this->render('Post/createPost.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/removePost/{postId}', name: "rmPost")]
    public function removePost(ManagerRegistry $doctrine, Int $postId)
    {
        //todo : vérifier également s'il s'agit de l'utilisateur ayant créer le post
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $post = $doctrine->getRepository(Post::class)->find($postId);
        if ($post != null) {
            $entity = $doctrine->getManager();
            $entity->remove($post);
            $entity->flush();
        }

        return $this->redirectToRoute('app_allPostAdmin');
    }
}
