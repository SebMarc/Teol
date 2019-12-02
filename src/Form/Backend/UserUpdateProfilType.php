<?php

namespace App\Form\Backend;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




class UserUpdateProfilType extends AbstractType
{
    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;

    /**
     * @Required
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function setPasswordEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
      
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'mapped'   => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Laisser vide si inchangé',
                ],
            ])
            
            ->add('technicien', EntityType::class, [
                'class'         => User::class,
                'multiple'      => false,
                'query_builder' => function(UserRepository $repository)  {
                  return $repository->findAllTechnicienForm();
                }
            ])
            

            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Adhérent'    => 'ROLE_MEMBER',
                    'Technicien' => 'ROLE_TECH',
                    'Admin'     => 'ROLE_ADMIN',
                ],
            ])
           
            ->add('firstname', TextType::class, [
                'label' => 'Prenom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            
            ->add('phone', TextType::class, [
                'label' => 'Telephone'
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('society', TextType::class, [
                'label' =>'Société'
            ])
            ->add('enable', CheckboxType::class, [
                'label'    => false,
                'required' => false,
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'onSubmit']
            )
        ;
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

    public function onSubmit(FormEvent $event)
    {
        /** @var User $user */
        $user = $event->getData();

        $form = $event->getForm();
        $password = $form->get('password')->getNormData();

        if ($password) {
            $encodedPassword = $this->encoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);
        }
    }
}
