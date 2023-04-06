<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;

class CreateCategoryController extends AbstractController
{

    #[Route('/createCategory')]
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
