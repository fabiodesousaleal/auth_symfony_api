<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('usuario')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$q3R5R7rWGXz49dMJ8yQkMA$o9PAg8dVfZEVE9SbOe5xerwR8oOtkZmXxIqhQrBy9u4');
        $manager->persist($user);
        $manager->flush();
    }
}
