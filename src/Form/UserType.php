<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('name')
            ->add('firstname')
            ->add('phone')
            ->add('email')
            ->add('password')
            ->add('password')
            ->add('campus', EntityType::class, ['class' => Campus::class,
                'choice_label' => 'name',
                    'label' => 'Campus'
            ])
            ->add('picture', FileType::class, [
                //ajouter champs dans dans formulaire qui ne sont pas dans l'entité
                //path du fichier temporaire
                'mapped' =>false,
                'constraints' =>[
                    new Image([
                        "maxSize"=>'7000k',
                        "mimeTypesMessage" => "Image format not allowed !",

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
