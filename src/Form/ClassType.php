<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> "Class Name",
                'required'=> true
            ])
            ->add('course',EntityType::class,[
                'label'=>"Course",
                'class'=> Course::class,
                'choice_label'=>"name",
                'multiple'=>true,
                'expanded'=>false
            ])
            ->add('student',EntityType::class,[
                'label'=>"Student",
                'class'=> Student::class,
                'choice_label'=>"name",
                'multiple'=>true,
                'expanded'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}