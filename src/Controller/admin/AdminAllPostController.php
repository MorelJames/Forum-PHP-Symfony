<?php

namespace App\Controller\admin;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\ChooseCategoryType;
use Doctrine\Persistence\ManagerRegistry;

class AdminAllPostController extends AbstractController
{

    #[Route('/allPostAdmin', name: 'app_allPostAdmin')]
    public function base(ManagerRegistry $doctrine, Request $request)
    {
        // autorise uniquement les administrateurs, sinon erreur
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $form = $this->createForm(ChooseCategoryType::class);

        $form->handleRequest($request);

        $allPost = $doctrine->getRepository(Post::class)->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $category = $doctrine->getRepository(Category::class)->find($data['Category']->getId());

            return $this->render('Post/AllPost.html.twig',[
                'allPost' => $category->getPosts(),
                'form' => $form->createView(),
            ]);
        }

        

        return $this->render('Post/AllPost.html.twig', [
            'allPost' => $allPost,
            'form' => $form->createView(),
        ]);
    }
}
