<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=15; $i++) {
            $teacher = new Teacher();
            $teacher->setName("Teacher No. $i");
            $teacher->setAge(rand(20,40));
            $teacher->setPhone(0111122223333);
            $teacher->setAddress("Lao Cai $i");
            $teacher->setImage("teacher.png");

            $manager->persist($teacher);
        }

        $manager->flush();
    }
}