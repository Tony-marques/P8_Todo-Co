<?php

namespace App\tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function testTaskListPage()
    {
        $client = $this->createClient();

        $client->request(Request::METHOD_GET, "/tasks");

        $container = static::getContainer();

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

    public function testCreateTask(){
        $client = $this->createClient();
        
        $crawler = $client->request(Request::METHOD_GET, '/tasks/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'nouveau titre 2',
            'task[content]' => 'nouveau contenu 2'
        ]);

        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a été bien été ajoutée.");
        

        // $container = static::getContainer();

        // /** @var TaskRepository */
        // $taskRepository = $container->get(TaskRepository::class);
        // $taskCreated = $taskRepository->findOneByTitle('nouveau titre 2');

        // $this->assertSame('nouveau titre 2',$taskCreated->getTitle());
        
    }
}