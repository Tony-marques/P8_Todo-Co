<?php

namespace App\tests\Repository;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
  public function testGetTask(){
    $taskRepository = static::getContainer()->get(TaskRepository::class);
    
    $task = $taskRepository->find(['id' => 1]);

    // dd($task);

    $this->assertSame(1, $task->getId());
  }
}