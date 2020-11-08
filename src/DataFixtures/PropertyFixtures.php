<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Property;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PropertyFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        
        for ($i=0; $i < 100 ; $i++) { 
            
            $word = $faker->words(2, true);

            $property= new Property();
            $property->setTitle(ucwords($word))
                        ->setPrice($faker->numberBetween(50000,10000000))
                        ->setRooms($faker->numberBetween(2,10))
                        ->setBedrooms($faker->numberBetween(1,9))
                        ->setSold(false)
                        ->setSurface($faker->numberBetween(20,350))
                        ->setFloor($faker->numberBetween(0,15))
                        ->setHeat($faker->numberBetween(0, count(Property::HEAT)-1))
                        ->setPostalCode($faker->postcode)
                        ->setCity($faker->city)
                        ->setDescription($faker->sentences(3,true))
                        ->setAddress($faker->address);
            
            $manager->persist($property);
            $manager->flush();
        }
            
    }
}
