<?php

namespace App\Form;

use App\Entity\Magasin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'])
            ->add('adress', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telephone',
                'mapped' => true
            ])
            ->add('fax', TextType::class, [
                'label' => 'Fax',
                'mapped' => true
            ])
            ->add('close', TextType::class, [
                'label' => 'Fermeture',
                'mapped' => true
            ])
            ->add('manager', TextType::class, [
                'label' => 'Manager',
                'mapped' => true
            ])
            ->add('enable', CheckboxType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Magasin::class,
        ]);
    }
}
