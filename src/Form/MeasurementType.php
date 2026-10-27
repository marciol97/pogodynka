<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => fn (Location $loc) => sprintf('%s (%s)', $loc->getCity(), $loc->getCountry()),
                'placeholder' => 'Choose location',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('temp_celsius', NumberType::class, [
                'scale' => 0,
                'attr' => [
                    'placeholder' => 'np. 21°C', ],
            ])
            ->add('humidity', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'np. 65%',
                ],
            ])
            ->add('pressure', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'np. 1015 hPa',
                ],
            ])
            ->add('cloudiness', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'np. 30',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
