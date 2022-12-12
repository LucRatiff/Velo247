<?php

namespace App\Form\Type;

use App\Service\AdminTools;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminToolsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', TextType::class, ['required' => false, 'label' => 'Nouvelle catégorie'])
            ->add('categoryPosition', IntegerType::class, ['required' => false, 'label' => 'Position de la catégorie'])
            ->add('categoryModerationOnly', CheckboxType::class, ['required' => false, 'label' => 'Réservé à la modération'])
            ->add('subCategory', TextType::class, ['required' => false, 'label' => 'Nouvelle sous-catégorie'])
            ->add('parentCategoryName', TextType::class, ['required' => false, 'label' => 'Catégorie parente de cette sous-catégorie'])
            ->add('subCategoryPosition', IntegerType::class, ['required' => false, 'label' => 'Position de la sous-catégorie'])
            ->add('subCategoryDescription', TextType::class, ['required' => false, 'label' => 'Description de la sous-catégorie'])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminTools::class,
        ]);
    }
}
