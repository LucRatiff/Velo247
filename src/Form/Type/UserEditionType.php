<?php

namespace App\Form\Type;

use App\Service\NewTopic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['required' => false, 'label' => 'Nom'])
            ->add('email', EmailType::class, ['required' => false, 'label' => 'Email'])
            ->add('password', PasswordType::class, ['required' => false, 'label' => 'Mot de passe'])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ville'])
            ->add('country', TextType::class, ['required' => false, 'label' => 'Pays'])
            ->add('signature', TextareaType::class, ['required' => false, 'label' => 'Signature'])
            ->add('photo', FileType::class, ['required' => false, 'label' => 'Photo'])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ville'])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewTopic::class,
        ]);
    }
}
