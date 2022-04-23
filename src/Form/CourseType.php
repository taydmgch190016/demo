<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> "Class Name",
                'required'=> true
            ])
            ->add('description',TextType::class,[
                'label'=> "Description",
                'required'=> true
            ])
           
            ->add('category',EntityType::class,[
                'label'=>"Category",
                'class'=> Category::class,
                'choice_label'=>"name", 
            ])
            ->add('teacher',EntityType::class,[
                'label'=>"Teacher",
                'class'=> Teacher::class,
                'choice_label'=>"name",              
            ])
            // ->add('classrooms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}