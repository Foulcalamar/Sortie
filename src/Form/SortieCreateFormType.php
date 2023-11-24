<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Entity\Campus;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SortieCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $organiseurDefaultValue = $options['organiseur_default'] ?? null;

        $builder
            ->add('nom', TextType::class, [
                'required' => true,
            ])

            ->add('dateHeureDebut', DateTimeType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => new DateTime(),
                        'message' => 'The start date and time must be in the future.'
                    ]),
                ],
            ])
            ->add('duree', IntegerType::class, [
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Please enter a valid number.',
                    ]),
                ],
            ])

            ->add('dateLimiteInscription', DateTimeType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => new DateTime(),
                        'message' => 'The registration deadline must be in the future.',
                    ]),
                ],
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'required' => true,
            ])
            ->add('infosSortie', TextareaType::class, [
                'required' => true,
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Select Campus',
                'required' => true,
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'Select Ville',
                'required' => true,
                'attr' => ['class' =>
                    'ville-select'],
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'placeholder' => 'Lieu',
                'required' => true,
                'attr' => ['class' =>
                    'lieu-select'],
            ])

            ->add('participantOrganisateur', HiddenType::class, [
                'data' => $organiseurDefaultValue,
                'mapped' => false,
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'organiseur_default' => null,
        ]);
    }
}
