<?php

namespace App\Form\Type;

use App\Service\NewTopic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['required' => true, 'label' => 'Titre'])
            ->add('message', TextareaType::class, ['required' => true, 'label' => 'Message'])
            ->add('save', SubmitType::class, ['label' => 'Nouveau sujet'])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewTopic::class,
        ]);
    }
}
