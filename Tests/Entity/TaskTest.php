<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testTaskEntity()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = (new User())
        ->setId(1)
        ->setEmail("tony.marques@live.fr")
        ->setUsername("toto")
        ->setPassword("12345")
        ->setRoles(["ROLE_ADMIN"]);

        $task = (new Task())
        ->setId(1)
        ->toggle(true)
        ->setContent("Contenu de la tâche")
        ->setTitle("Titre de la tâche")
        ->setCreatedAt(new DateTimeImmutable())
        ->setUser($user);

        $errors = $container->get("validator")->validate($task, null, groups:["user:create"]);

        $this->assertCount(0, $errors);
        $this->assertSame(1, $task->getId());
        $this->assertSame(true, $task->isDone());
        $this->assertSame($user, $task->getUser());
        $this->assertSame("Titre de la tâche", $task->getTitle());
        $this->assertSame("Contenu de la tâche", $task->getContent());

    }
}
