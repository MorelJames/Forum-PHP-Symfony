<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;

class AdminDashboard extends AbstractController{

    public function __construct(
        private Security $security,
    ){}

    #[Route('/adminDashboard', name:'app_admindashboard')]
    public function index(ManagerRegistry $doctrine){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        if(!$user->isVerified()) {
            return $this->render('requireValidation.html.twig');
        }

        return $this->render('adminDashboard.html.twig',[
            'user'=>$this->security->getUser(),
        ]);
    }

    public function setAdmin(ManagerRegistry $doctrine)
    {
        # TODO : gérer le fait que l'utilisateur de la session n'est pas mis à jour
        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_ADMIN']);
        $entity = $doctrine->getManager();
        $entity->flush();
    }

    public function setBlogger(ManagerRegistry $doctrine)
    {
        # TODO : gérer le fait que l'utilisateur de la session n'est pas mis à jour
        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_BLOG']);
        $entity = $doctrine->getManager();
        $entity->flush();
    }

    #[Route('/signaledPosts', name:'app_signaledPosts')]
    public function showSignaledPosts(ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $allPosts = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('/SignaledPosts.html.twig', [
            'allPosts' => $allPosts,
        ]);
    }

    #[Route('/validatePost/{postId}', name: 'app_validate_post')]
    public function validatePost(Request $request, ManagerRegistry $doctrine, Int $postId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $post = $doctrine->getRepository(Post::class)->find($postId);
        $post->setSignaled(false);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('app_signaledPosts');
    }

    #[Route('/admin/deletePost/{postId}', name: 'app_admin_rmpost')]
    public function deletePost(ManagerRegistry $doctrine, Int $postId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $post = $doctrine->getRepository(Comment::class)->find($postId);
        $entity = $doctrine->getManager();
        $entity->remove($post);
        $entity->flush();
        return $this->redirectToRoute('app_signaledPosts');
    }

    #[Route('/signaledComments', name:'app_signaledComments')]
    public function showSignaledComments(ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $allComments = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('/SignaledComments.html.twig', [
            'allComments' => $allComments,
        ]);
    }

    #[Route('/validateComment/{commentId}', name: 'app_validate_comment')]
    public function validateComment(ManagerRegistry $doctrine, Int $commentId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $comment = $doctrine->getRepository(Post::class)->find($commentId);
        $comment->setSignaled(false);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('app_signaledPosts');
    }

    #[Route('/admin/deleteComment/{commentId}', name: 'app_admin_rmcomment')]
    public function deleteComment(ManagerRegistry $doctrine, Int $commentId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $comment = $doctrine->getRepository(Comment::class)->find($commentId);
        $entity = $doctrine->getManager();
        $entity->remove($comment);
        $entity->flush();
        return $this->redirectToRoute('app_signaledComments');
    }
}