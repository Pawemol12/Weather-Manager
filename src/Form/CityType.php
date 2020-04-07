<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\City;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'city.name',
                'required' => true
            ])
            ->add('state', TextType::class, [
                'label' => 'city.state',
                'required' => false
            ])
            ->add('apiCityId', IntegerType::class, [
                'label' => 'city.apiCityId',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['id' => 'CityForm'],
            'data_class' => City::class
        ]);
    }
}