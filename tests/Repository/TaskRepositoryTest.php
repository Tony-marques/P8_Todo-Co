<?php

namespace App\tests\Repository;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
  public function testGetTask(){
    $taskRepository = static::getContainer()->get(TaskRepository::class);
    
    /** @var Task */
    $task = $taskRepository->findOneBy(['title' => "testGetTask"]);

    $this->assertSame("testGetTask", $task->getTitle());
  }
}