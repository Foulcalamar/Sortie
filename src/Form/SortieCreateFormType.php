<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class)
            ->add('duree', IntegerType::class)
            ->add('dateLimiteInscription', DateTimeType::class)
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('infosSortie', TextareaType::class)
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Select Campus',
                'required' => true,
                'mapped' => false,
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'Select Ville',
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'ville-select'],
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'placeholder' => 'Select Lieu',
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'lieu-select'],
            ])
            ->add('rue', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'rue-field'],
            ])
            ->add('codePostal', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'codePostal-field'],
            ])
            ->add('latitude', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'latitude-field'],
            ])
            ->add('longitude', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
