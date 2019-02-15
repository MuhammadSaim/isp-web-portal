<?php

namespace App\Form;

use App\Entity\Departments;
use App\Entity\Sections;
use App\Entity\Semesters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddLectureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('department', EntityType::class, [
                'class' => Departments::class,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ],
                'required' => true
            ])
            ->add('course', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper',
                    'disabled' => true
                ],
                'required' => true
            ])
            ->add('semester', EntityType::class, [
                'class' => Semesters::class,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ],
                'required' => true
            ])
            ->add('section', EntityType::class, [
                'class' => Sections::class,
                'attr' => [
                    'class' => 'form-control select2',
                    'data-parsley-class-handler' => '#slParsleyWrapper'
                ],
                'required' => false
            ])
            ->add('file', FileType::class, [
                'label' => 'Lecture',
                'label_attr' => [
                    'class' => 'custom-file-label custom-file-label-primary'
                ],
                'attr' => [
                    'class' => 'custom-file-input'
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
