<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i<=15; $i++) {
            $student = new Student();
            $student->setName("Student No. $i");
            $student->setAge(rand(18,25));
            $student->setPhone(0111122223333);
            $student->setAddress("Nam Dinh");
            $student->setImage("avatar.jpg");

            $manager->persist($student);
        }
        $manager->flush();
    }
}