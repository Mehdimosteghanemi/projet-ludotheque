<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{

    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        /* Step 1 */
            ->add('email')
            ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $user = $event->getData();
            $form = $event->getForm();

            if ($user->getId() === null) {
                $form->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Merci de saisir un mot de passe d\'au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
                ;
            }
        });
            

        $builder
        /* Step 2 */
            ->add('firstname')
            ->add('lastname')
            ->add('address_road')
            ->add('address_number')
            ->add('address_city')
            ->add('address_zip_code', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Renseignez le code postal s\'il vous plaît.'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class)
            ;

        if ($this->user) {
            if (in_array('ROLE_SUPER_ADMIN', $this->user->getRoles())) {
                $builder
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        // 'Selectionez' => null,
                        'user' => 'ROLE_USER',
                        'admin' => 'ROLE_ADMIN',
                        'super admin' => 'ROLE_SUPER_ADMIN',
                    ],
                    'multiple' => true,
                    'expanded' => true,
                    'row_attr' => ['class' => 'input-text__row'],
                    'label' => 'Roles',
                    'attr' => ['class' => 'input-text__row__input'],
                    // 'label_attr' => ['class' => 'input-text__row__label'],
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => User::class,
        ]);
    }
}