<?php

namespace App\Form;

use App\Form\Filter\AdminCampuses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCampusesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', SearchType::class, [
                'label' => 'Le nom contient : ',
                'attr' => ['placeholder' => 'Campus']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminCampuses::class,
            'required' => false
        ]);
    }
}
