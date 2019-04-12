<?php

namespace App\Form;

use App\Entity\Departments;
use App\Entity\Designations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()

                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
                'label_attr' => [
                    'class' => 'form-control-label'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Chose gender' => '',
                    'Male' => 'male',
                    'Female' => 'female',
                    'Other' => 'other',
                ],
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ]
            ])
            ->add('department', EntityType::class, [
                'class' => Departments::class,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ]
            ])
            ->add('designation', EntityType::class, [
                'class' => Designations::class,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'choices' => [
                    'Teacher'            => 'ROLE_TEACHER',
                    'Admin'              => 'ROLE_ADMIN',
                    'Coordinator'        => 'ROLE_COORDINATOR',
                    'Course Coordinator' => 'ROLE_COURSE_COORDINATOR',
                    'Examiner'           => 'ROLE_EXAMINER',
                ],
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
