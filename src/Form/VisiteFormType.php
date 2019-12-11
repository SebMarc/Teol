<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Visite;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;


class VisiteFormType extends AbstractType
{
    private $ur;
    private $security;
   

    public function __construct(UserRepository $ur, Security $security)
    {
        $this->ur = $ur;
        $this->security = $security;
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class, [
                'label' => 'Résumé de la visite',
                'required' => true
            ])
            ->add('contentdetails', TextareaType::class, [
                'label' => 'Détails de la visite',
                'required' => true
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date',
                
            ])
            ->add('user', EntityType::class, [
                'label' => 'Adhérent',
                'class' => User::class,
                'choices' => $this->ur->findAllClientByTechnicienQuery($this->security->getUser()->getEmail()),
                'choice_label' => function(User $user) {
                    return $user->getSociety();
                },
              
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}
