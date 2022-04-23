<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> "Full Name",
                'required'=> true
            ])
            ->add('age', IntegerType::class,[
                'label'=>"Age",
                'required'=> true
            ])
            ->add('phone',TextType::class,[
                'label'=> "Phone number",
                'required'=> true
            ])
            ->add('image',FileType::class,[
                'label' =>"Student Avatar",
                'data_class' =>null,
                'required'=> is_null($builder->getdata()->getImage())
            ])
            ->add('address',TextType::class,[
                'label'=> "Address",
            ])
            // ->add('classrooms',EntityType::class,[
            //     'label'=>"Class",
            //     'class'=> Classroom::class,
            //     'choice_label'=>"name",
            //     'multiple'=>true,
            //     'expanded'=>false
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}