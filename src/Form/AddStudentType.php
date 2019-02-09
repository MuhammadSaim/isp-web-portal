<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Departments;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddStudentType extends AbstractType
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
            ->add('regno', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Reg No.'
                ],
                'label' => 'Reg No.',
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
            ->add('departments', EntityType::class, [
                'class' => Departments::class,
                'attr' => [
                    'class' => 'form-control s2-depart',
                    'data-placeholder' => "Choose Department"
                ],
                'required' => true
            ])
            ->add('courses', EntityType::class, [
                'class' => Courses::class,
                'attr' => [
                    'class' => 'form-control s2-courses',
                    'data-placeholder' => "Choose Course"
                ],
                'required' => true
            ])
            ->add('is_CR_GR', ChoiceType::class, [
                'choices' => [
                    'Cr or Gr' => '',
                    'NO' => 'no',
                    'Yes' => 'yes',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'data-placeholder' => "Choose country"
                ],
                'required' => true
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
