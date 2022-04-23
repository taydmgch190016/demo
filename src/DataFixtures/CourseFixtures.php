<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i<=5; $i++) {
            $course = new Course();
            $course->setName("Course $i");
            $course->setDescription("Awesome ");
            $manager->persist($course);
        }
        $manager->flush();
    }
}