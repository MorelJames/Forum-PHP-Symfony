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
use App\Form\UserRoleType;
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

    #[Route('/manageUserRole', name:'app_adminMangeUserRole')]
    public function manageUserRole(ManagerRegistry $doctrine, Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $form = $this->createForm(UserRoleType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();
            $addRole = $form->get('setRole')->getData();
            if ($addRole==0) {
                $this->setAdmin($doctrine, $user->getId());
            }elseif($addRole==1){
                $this->setBlogger($doctrine, $user->getId());
            }else{
                $this->setUser($doctrine, $user->getId());
            }        

            return $this->redirectToRoute('app_admindashboard');
        }

        return $this->render('ManageUserRole.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    public function setAdmin(ManagerRegistry $doctrine, int $userId)
    {
        # TODO : gérer le fait que l'utilisateur de la session n'est pas mis à jour
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_ADMIN']);
        $entity = $doctrine->getManager();
        $entity->flush();
    }

    public function setBlogger(ManagerRegistry $doctrine, int $userId)
    {
        # TODO : gérer le fait que l'utilisateur de la session n'est pas mis à jour
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_BLOG']);
        $entity = $doctrine->getManager();
        $entity->flush();
    }

    public function setUser(ManagerRegistry $doctrine, int $userId)
    {
        # TODO : gérer le fait que l'utilisateur de la session n'est pas mis à jour
        $user = $doctrine->getRepository(User::class)->find($userId);
        $user->setRoles(['ROLE_USER']);
        $entity = $doctrine->getManager();
        $entity->flush();
    }

    #[Route('/signaledPosts', name:'app_signaledPosts')]
    public function showSignaledPosts(ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $allPosts = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('Post/SignaledPosts.html.twig', [
            'allPosts' => $allPosts,
        ]);
    }

    #[Route('/validatePost/{postId}', name: 'app_validate_post')]
    public function validatePost(Request $request, ManagerRegistry $doctrine, Int $postId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $post = $doctrine->getRepository(Post::class)->find($postId);
        $post->setSignaled(false);
        $post->setReporteAt(null);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('app_signaledPosts');
    }

    #[Route('/admin/deletePost/{postId}', name: 'app_admin_rmpost')]
    public function deletePost(ManagerRegistry $doctrine, Int $postId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $post = $doctrine->getRepository(Post::class)->find($postId);
        $entity = $doctrine->getManager();
        $entity->remove($post);
        $entity->flush();
        return $this->redirectToRoute('app_signaledPosts');
    }

    #[Route('/signaledComments', name:'app_signaledComments')]
    public function showSignaledComments(ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $allComments = $doctrine->getRepository(Comment::class)->findAll();

        return $this->render('Comment/SignaledComments.html.twig', [
            'allComments' => $allComments,
        ]);
    }

    #[Route('/validateComment/{commentId}', name: 'app_validate_comment')]
    public function validateComment(ManagerRegistry $doctrine, Int $commentId) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $comment = $doctrine->getRepository(Comment::class)->find($commentId);
        $comment->setSignaled(false);
        $comment->setReportedAt(null);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('app_signaledComments');
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