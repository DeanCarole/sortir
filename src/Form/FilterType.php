<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\Filter\Filter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, ['class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus '
            ])
            ->add('name', SearchType::class, [
                'label' => 'Le nom de la sortie contient : '
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Entre "
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "et "
            ])
            ->add('eventsPlanned', CheckboxType::class, [
                'label' => "Sorties dont je suis l'organisateur/trice"
            ])
            ->add('eventsRegistered', CheckboxType::class, [
                'label' => "Sorties auxquelles je suis inscrit/e"
            ])
            ->add('eventsNotRegistered', CheckboxType::class, [
                'label' => "Sorties auxquelles je ne suis pas inscrit/e"
            ])
            ->add('eventsPassed', CheckboxType::class, [
                'label' => "Sorties passées"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'required' =>false
        ]);
    }
}
