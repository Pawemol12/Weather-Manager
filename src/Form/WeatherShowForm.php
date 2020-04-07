<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\City;

class WeatherShowForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'label' => 'city.name',
                'class' => City::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('show', SubmitType::class, [
                'label' => 'show'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['id' => 'WeatherSearchForm'],
        ]);
    }
}