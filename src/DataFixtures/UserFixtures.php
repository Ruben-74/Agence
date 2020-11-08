<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    //encodeur pour crypter le mdp
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
       $user= new User();
       $user->setUsername("ruben")
            ->setPassword($this->encoder->encodePassword($user, 'azerty'));

        $manager->persist($user);
        $manager->flush();
    }
}
