<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Unique;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo: '
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom : '
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom : '
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone : '
            ])
            ->add('email', TextType::class, [
                'label' => 'Email : '
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de Passe : ',

                ],
                'second_options' => [
                    'label' => 'Nouveau mot de passe : ',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'required'=>false,
                'mapped' => false,
            ])
            ->add('campus', EntityType::class, ['class' => Campus::class,
                'choice_label' => 'name',
                    'label' => 'Campus : '
            ])
            ->add('picture', FileType::class, [
                //ajouter champs dans dans formulaire qui ne sont pas dans l'entité
                //path du fichier temporaire
                'required'=>false,
                'mapped' =>false, 'label' => 'Modifier Image : ',
                'constraints' =>[
                    new Image([
                        "maxSize"=>'7000k',
                        "mimeTypesMessage" => "Ce format n'est pas pris en charge !"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
