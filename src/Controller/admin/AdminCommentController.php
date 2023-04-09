<?php

namespace App\Controller\admin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class AdminCommentController extends AbstractController{
    
    public function __construct(private Security $security){}

    #[Route('/commentAdmin/{name}', methods: ['GET'])]
    public function base($name){

        return $this->render('/AdminComment.html.twig',[
            'name'=>$name,
        ]);
    }

    #[Route('/signalComment/{commentId}', name: 'app_signal_comment')]
    public function signalComment(ManagerRegistry $doctrine, Int $commentId) : Response
    {
        $userId = $this->security->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userId);

        if(!$user->isVerified()) {
            return $this->render('requireValidation.html.twig');
        }
        
        $comment = $doctrine->getRepository(Post::class)->find($commentId);
        $comment->setSignaled(true);
        $entity = $doctrine->getManager();
        $entity->flush();

        return $this->redirectToRoute('/commentAdmin/'.$commentId);
    }
}