<?php

namespace App\Form;

use App\Entity\Departments;
use App\Entity\Programs;

use App\Entity\Sections;
use App\Entity\Semesters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
                'label_attr' => [
                    'class' => 'form-control-label'
                ]
            ])
            ->add('regno', TextType::class, [
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
            ->add('programs', EntityType::class, [
                'class' => Programs::class,
                'attr' => [
                    'class' => 'form-control s2-courses',
                    'data-placeholder' => "Choose Course"
                ],
                'required' => true
            ])
            ->add('semesters', EntityType::class, [
                'class' => Semesters::class,
                'attr' => [
                    'class' => 'form-control s2-courses',
                    'data-placeholder' => "Choose Course"
                ],
                'required' => true
            ])
            ->add('sections', EntityType::class, [
                'class' => Sections::class,
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
