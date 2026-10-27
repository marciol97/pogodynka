<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', null, [ 'attr' => [
                'placeholder' => 'Enter city name',
                ],])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Italy' => 'IT',
                    'Spain' => 'ES',
                    'United States' => 'US',
                    'United Kingdom' => 'GB',
                    'Colombia' => 'CO',
                ]
            ])
            ->add('latitude', NumberType::class, [
                'scale' => 7,])
            ->add('longitude', NumberType::class, [
                'scale' => 7,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
