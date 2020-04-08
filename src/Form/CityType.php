<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\City;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class CityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['id' => 'CityForm'],
            'data_class' => City::class
        ]);
    }
}