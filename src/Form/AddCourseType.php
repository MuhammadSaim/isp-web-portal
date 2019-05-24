<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Departments;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('course', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Course',
                    'pattern'     => "[a-zA-Z]+",
                    "data-parsley-trigger" => "keyup"
                ],
                'label_attr' => [
                    'class' => 'form-control-label'
                ]
            ])
            ->add('courseCode', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Course Code',
                    'pattern'     => "([A-Z]{2,})[\-](\d)+",
                    "data-parsley-trigger" => "keyup"
                ],
                'label_attr' => [
                    'class' => 'form-control-label'
                ]
            ])
            ->add('creditHours', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Credit Hours',
                    'pattern'     => "([A-Z]{2,})[\-](\d)+",
                    "data-parsley-trigger" => "keyup"
                ],
                'label_attr' => [
                    'class' => 'form-control-label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Courses::class
        ]);
    }
}
