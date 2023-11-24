<?php

namespace App\Form;

use App\Data\SortieRecherche;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieRechercheForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Campus::class,
                'multiple' => false
            ])
            ->add('cle', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateFrom', DateType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'dateFrom'
                ],
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('dateTo', DateType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'dateTo'
                ],
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('organisateurRecherche', CheckboxType::class, [
                'required' => false,
            ])
            ->add('inscritsRecherche', CheckboxType::class, [
                'required' => false,
            ])
            ->add('noninscritsRecherche', CheckboxType::class, [
                'required' => false,
            ])
            ->add('pasee', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortieRecherche::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}