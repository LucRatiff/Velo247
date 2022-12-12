<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Form\Type\AdminToolsType;
use App\Service\AdminTools;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    public function index(Request $request, ManagerRegistry $registry): Response
    {
        $adminTools = new AdminTools();
        $form = $this->createForm(AdminToolsType::class, $adminTools);
        $form->handleRequest($request);
        $error = null;
        $success = null;
        
        if ($form->isSubmitted() && $form->isValid()) {
            $adminTools = $form->getData();
            
            if ($adminTools->getCategory() != null) {
                if ($adminTools->getCategoryPosition() == null) {
                    $error = 'Il manque des informations pour créer une catégorie';
                } else {
                    $category = (new Category())->hydrate($adminTools->getCategory(),
                            $adminTools->getCategoryPosition(), $adminTools->getCategoryModerationOnly());
                    $manager = $registry->getManager();
                    $manager->persist($category);
                    $manager->flush();
                }
            } else if ($adminTools->getSubCategory() != null) {
                if ($adminTools->getParentCategoryName() == null
                        || $adminTools->getSubCategoryPosition() == null)
                {
                    $error = 'Il manque des informations pour créer une sous-catégorie';
                } else {
                    $category = $registry->getRepository(Category::class)->findOneBy(['name' => $adminTools->getParentCategoryName()]);
                    if (!$category) {
                        $error = 'La catégorie parente n\'a pas été trouvée';
                    } else {
                        $subCategory = (new SubCategory())->hydrate($adminTools->getSubCategory(),
                                $category, $adminTools->getSubCategoryPosition(),
                                $adminTools->getSubCategoryDescription());
                        $manager = $registry->getManager();
                        $manager->persist($subCategory);
                        $manager->flush();
                        $manager->clear();
                        $subCategory = $registry->getRepository(SubCategory::class)->findOneBy(['name' => $adminTools->getSubCategory()]);
                        $category->addSubCategory($subCategory);
                        $manager->persist($category);
                        $manager->flush();
                    }
                }
            } else {
                $error = 'Soit on crée une catégorie, soit on crée une sous-catégorie en précisant ses informations';
            }
            
            if ($error == null) {
                $success = 'Forum édité avec succès';
            }
        }
        
        return $this->renderForm('admin/admin_tools.html.twig', [
            'success' => $success,
            'error' => $error,
            'form' => $form,
        ]);
    }
}
