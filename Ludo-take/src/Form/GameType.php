<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
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
            ->add('description', null, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Description',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ]) 
            ->add('images', null, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'Image',
                'attr' => ['class' => 'input-text__row__input'],
                'label_attr' => ['class' => 'input-text__row__label'],
            ]) 
            ->add('difficulty', null, [
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
            ->add('time_of', null, [
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
            ->add('available', null, [
                'row_attr' => ['class' => 'input-text__row'],
                'label' => 'disponible',
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
