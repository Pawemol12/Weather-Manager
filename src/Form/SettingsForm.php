<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Paweł Lodzik <Pawemol12@gmail.com>
 */
class SettingsForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apiKey', TextType::class, [
                'label' => 'settings.apiKey',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('weatherInfoByCityAndStateLink', TextType::class, [
                'label' => 'settings.weatherInfoByCityAndStateLink',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('weatherInfoByCityIdLink', TextType::class, [
                'label' => 'settings.weatherInfoByCityIdLink',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('weatherInfoByCityLink', TextType::class, [
                'label' => 'settings.weatherInfoByCityLink',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
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
            'attr' => ['id' => 'SettingsForm'],
        ]);
    }
}