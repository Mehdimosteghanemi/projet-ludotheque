<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Nom',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ]) 
            ->add('description', TextareaType::class, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Description',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ]) 
            ->add('images', UrlType::class, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Image',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ]) 
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    'Selectionez' => null,
                    'Enfantin' => 'Enfantin',
                    'Simple' => 'Simple',
                    'Famillial' => 'Famillial',
                    'Avertie' => 'Avertie',
                    'Expert' => 'Expert',
                ],
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Difficultée',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ]) 
            ->add('players', null, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Nombre de joueurs',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ])
            ->add('time_of', ChoiceType::class, [
                'choices' => [
                    'Selectionez' => null,
                    'Moins de 20 min' => 20,
                    'Entre 30min et 1h' => 45,
                    'Entre 1 et 2h' => 90,
                    'Entre 2 et 3h' => 150,
                    'Plus de 3h' => 180,
                ],
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Durée moyenne d\'une partie',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ])
            ->add('stock', null, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Stock',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
