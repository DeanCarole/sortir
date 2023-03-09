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
            ->add('name', TextType::class)
           ->add('startDateTime',  DateTimeType::class)
            ->add('registrationDeadline', DateType::class)
            ->add('nbRegistrationMax', TextType::class)
            ->add('duration', TextType::class)
            ->add('eventData', TextareaType::class)
            ->add('campus', EntityType::class, ['class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus'
            ])
            ->add ('place', EntityType::class, [
                'class' => Place::class,
                'choice_label'=>'name',
                'label' => 'Choisir un lieu'
            ])
//            ->add('place', EntityType::class, [
//            "class"=> Place::class,
//                'choice_label'=>'name',
//            "label"=> "Choisir un lieu"
//            ])
            ->add('place', EntityType::class, [
                "class"=> Place::class,
                'choice_label'=>'latitude',
                "label"=> "Choisir une latitude"
            ])
            ->add('place', EntityType::class, [
                "class"=> Place::class,
                'choice_label'=>'longitude',
                "label"=> "Choisir une longitude"
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
