<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder,array $options)
    {

        $builder
            ->add('email')
            ->add('roles',ChoiceType::class,[ 'choices'=>[
                'Administrateur' => 'ROLE_ADMIN',
                'Utilisateur' => 'ROLE_USER'
            ]
            ])
            ->add('password',PasswordType::class, ['mapped'=>false,'required' => false])
            ->add('pseudo')
        ;

        $builder->get('roles')
                ->addModelTransformer(new CallbackTransformer( function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                    function ($rolesString) {
                        // transform the string back to an array
                        return [$rolesString];
                    }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
