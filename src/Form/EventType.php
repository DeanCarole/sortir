<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])
           ->add('startDateTime',  DateTimeType::class, [
               'label' => 'Date et heure de la sortie : '
           ])
            ->add('registrationDeadline', DateType::class, [
                'label' => "Date limite d'inscription : "
            ])
            ->add('nbRegistrationMax', TextType::class, [
                'label' => 'Nombre de places : '
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée : '
            ])
            ->add('eventData', TextareaType::class, [
                'label' => 'Description et infos : '
            ])
            ->add('campus', EntityType::class, ['class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus : '
            ])
            ->add ('place', EntityType::class, ['class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu : '
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
