<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class CategoryController extends AbstractController
{

    #[Route('/Category/allCategory', name: 'app_allCategory')]
    public function AllCategory(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();

        $posts = $entityManager
                 ->getRepository(Post::class)->getThreeLastPosts();

        return $this->render('Category/AllCategory.html.twig', [
            'categories' => $categories,
            'newPosts' => $posts
        ]);
    }

    #[Route('/Category/{categId}', name: 'app_Category')]
    public function Category(ManagerRegistry $doctrine, Int $categId)
    {
        $category = $doctrine->getRepository(Category::class)->find($categId);

        return $this->render('Category/Category.html.twig', [
            'category' => $category,
        ]);
    }
    

    #[Route('/createCategory', name: 'app_createCategory')]
    public function createCategory(ManagerRegistry $doctrine, Request $request)
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entity = $doctrine->getManager();

            $entity->persist($category);
            $entity->flush();

            return $this->redirectToRoute('app_allPostAdmin');
        }

        return $this->render('/AdminPost.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
