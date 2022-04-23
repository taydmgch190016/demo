<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClassFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i<=5; $i++) {
            $classroom = new Classroom();
            $classroom->setName("Course $i");
            $manager->persist($classroom);
        }
        $manager->flush();
    }
}