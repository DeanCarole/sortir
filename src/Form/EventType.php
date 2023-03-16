<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])
           ->add('startDateTime',  DateTimeType::class, [
               'widget' => 'single_text',
               'label' => 'Date et heure de la sortie : '
           ])
            ->add('registrationDeadline', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date limite d'inscription : "
            ])
            ->add('nbRegistrationMax', TextType::class, [
                'label' => 'Nombre de places : '
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée (en minutes): '
            ])
            ->add('eventData', TextareaType::class, [
                'label' => 'Description et informations de la sortie : '
            ])
            ->add('campus', EntityType::class, ['class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus : ',
                'placeholder' => 'Choisir un campus'
            ])
            ->add ('place', EntityType::class, ['class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu : '
            ]);
//            ->add('city', EntityType::class, ['class' => City::class,
//                'choice_label' =>'name',
//                'label' => 'Ville : ',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('u')
//                        ->orderBy('u.username', 'ASC');
//                },

//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
