<?php

namespace App\tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
                
    }
    public function testEditTask(){
        $client = $this->createClient();

        $container = static::getContainer();
        
        $entityManager = $container->get(EntityManagerInterface::class);

        /** @var TaskRepository */
        $taskRepository = $entityManager->getRepository(Task::class);

        $task = $taskRepository->findOneBy(["id" => 1]);

        /** @var UrlGeneratorInterface */
        $urlGenerator = $container->get(UrlGeneratorInterface::class);
        
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate("task_edit", ["id" => $task->getId()]));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Titre modifié',
            'task[content]' => 'contenu modifié'
        ]);

        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été modifiée.");
                
    }
}