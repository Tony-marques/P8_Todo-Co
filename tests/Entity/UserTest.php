<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testUserEntity()
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = (new Task())
        ->setContent("Contenu de la tâche")
        ->setTitle("Titre de la tâche")
        ->setCreatedAt(new DateTimeImmutable());

        $user = (new User())
        ->setId(1)
        ->setEmail("tony.marques@live.fr")
        ->setUsername("toto")
        ->setPassword("12345")
        ->setRoles(["ROLE_ADMIN"])
        ->addTask($task);

        $errors = $container->get("validator")->validate($user, null, groups:["user:create"]);

        $this->assertCount(0, $errors);
        $this->assertSame(1, $user->getId());
        $this->assertSame("tony.marques@live.fr", $user->getEmail());
        $this->assertSame("toto", $user->getUsername());
        $this->assertSame("12345", $user->getPassword());
        $this->assertSame(["ROLE_ADMIN", "ROLE_USER"], $user->getRoles());
        $this->assertSame("tony.marques@live.fr", $user->getUserIdentifier());
        $this->assertSame($task, $user->getTasks()[0]);
    }

}
