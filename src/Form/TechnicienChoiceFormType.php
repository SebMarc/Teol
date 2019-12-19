<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TechnicienChoiceFormType extends AbstractType
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
            ->add('technicien', EntityType::class, [
                'class'         => User::class,
                'multiple'      => false,
                'label'         => false,
                'placeholder'   => 'Par Technicien',
                'choice_label'  => 'lastname',
                'query_builder' => function(UserRepository $repository)  {
                return $repository->findAllTechnicienForm();
                }
                ])
            
           ;
          
           $builder->get('technicien')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
               $tech = $event->getForm()->getData()->getEmail();
               //dump($tech);
               $form = $event->getForm();
               $form->getParent()->add('adherent', EntityType::class, [
                   'class'          => User::class,
                   'placeholder'    => 'Selection Adherent',
                   'mapped'         => false,
                   'label'          => false,
                   'required'       => false,
                   'auto_initialize'=> false,
                   'choice_label'   => 'society',
                   'query_builder'  => function(UserRepository $repository) use($tech)  {
                                    return $repository->findAllClientByTechnicienSupervision($tech);
                  }
                  
               ]);
              
            }
        );
        

     

       
    }
                

               

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
       
    }
}
