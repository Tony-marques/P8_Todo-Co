<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher){}
    // private UserPasswordHasherInterface $hasher;

    // public function __construct(UserPasswordHasherInterface $hasher){
    //     $this->hasher = $hasher;
    // }

    public function load(ObjectManager $manager): void
    {

        $user = new User();

        $user->setRoles(["ROLE_ADMIN"])
        ->setUsername("admin")
        ->setPassword($this->hasher->hashPassword($user, "12345"))
        ->setEmail("admin@gmail.com");

        $user1 = new User();
        $user1->setRoles(["ROLE_USER"])
        ->setUsername("user1")
        ->setPassword($this->hasher->hashPassword($user, "12345"))
        ->setEmail("user1@gmail.com");

        $task = new Task();
        $task->setTitle("testGetTask")
        ->setContent("test")
        ->toggle(0);

        $manager->persist($user);
        $manager->persist($user1);
        $manager->persist($task);
        $manager->flush();
    }
}
