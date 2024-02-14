<?php

namespace App\tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TastControllerTest extends WebTestCase
{
    public function testTaskListPage()
    {
        $client = $this->createClient();

        $client->request(Request::METHOD_GET, "/tasks");


        $container =static::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);

        /** @var TaskRepository */
        $taskRepository = $entityManager->getRepository(Task::class);


        $tasks = $taskRepository->findAll();

        foreach($tasks as $task){
            $this->assertInstanceOf(Task::class, $task);
        }

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}