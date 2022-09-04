<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Technicien;
use App\Entity\Administrateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');

        for ($i=0; $i < mt_rand(2, 3) ; $i++) { 
            $admin = new Administrateur();

            $admin->setFirstname($faker->firstname())
                    ->setLastname($faker->lastname())
                    ->setJob($faker->jobTitle())
                    ->setAddress($faker->address())
                    ->setcontact($faker->phoneNumber())
                    ->setEmail($faker->email())
                    ->setPassword($this->encoder->hashPassword($admin, "password"))
                    ->setPicture("avatar.png")
                    ->setRoles(['ROLE_ADMINISTRATEUR']);
            $manager->persist($admin);
        }

        for ($i=0; $i < mt_rand(10, 20) ; $i++) { 
            $tech = new Technicien();
            $tech
                ->setFirstname($faker->firstname())
                ->setLastname($faker->lastname())
                ->setMatricule(mt_rand(1000, 9999))
                ->setJob($faker->jobTitle())
                ->setAddress($faker->address())
                ->setcontact($faker->phoneNumber())
                ->setEmail($faker->email())
                ->setPassword($this->encoder->hashPassword($tech, "password"))
                ->setPicture("avatar.png")
                ->setState("Disponible")
                ->setRoles(['ROLE_TECHNICIEN']);
            $manager->persist($tech);
        }

        $manager->flush();
    }
}
