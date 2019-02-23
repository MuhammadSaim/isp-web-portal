<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Departments;

use App\Entity\Programs;
use App\Entity\Sections;
use App\Entity\Semesters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SemesterCourseMappingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('department', EntityType::class, [
                'class' => Departments::class,
                'attr' => [
                    'class' => 'form-control select2'
                ],
                'required' => true
            ])
            ->add('program', EntityType::class, [
                'class' => Programs::class,
                'attr' =>[
                    'class' => 'form-control select2'
                ],
                'required' => true
            ])
            ->add('semester', EntityType::class, [
                'class' => Semesters::class,
                'attr' =>[
                    'class' => 'form-control select2'
                ],
                'required' => true
            ])
            ->add('courses', EntityType::class, [
                'class' => Courses::class,
                'attr' =>[
                    'class' => 'form-control select2',
                ],
                'multiple' => true,
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
