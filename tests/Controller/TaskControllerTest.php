<?php

namespace App\tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;
    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;

    public function setUp():void{
        $this->client = static::createClient();

        $this->container = static::getContainer();

        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->taskRepository = $this->entityManager->getRepository(Task::class);

        $this->userRepository = $this->entityManager->getRepository(User::class);

        $this->urlGenerator = $this->container->get(UrlGeneratorInterface::class);
    }

    public function testTaskListPage()
    {
        $this->client->request(Request::METHOD_GET, "/tasks");

        $tasks = $this->taskRepository->findAll();

        foreach($tasks as $task){
            $this->assertInstanceOf(Task::class, $task);
        }

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateTask(){
        $crawler = $this->client->request(Request::METHOD_GET, '/tasks/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'nouveau titre',
            'task[content]' => 'nouveau contenu'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a été bien été ajoutée.");                
    }

    public function testEditTask(){
        $task = $this->taskRepository->findOneBy(["title" => "nouveau titre"]);
        
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_edit", ["id" => $task->getId()]));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Titre modifié',
            'task[content]' => 'contenu modifié'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été modifiée.");
    }

    public function testToggleTask(){
        /** @var Task */
        $task = $this->taskRepository->findOneBy(["title" => "nouveau titre"]);

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_toggle", ["id" => $task->getId()]));

        $task->toggle(!$task->isDone());

        $this->client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche {$task->getTitle()} a bien été marquée comme faite.");
    }

    public function testDeleteTask(){
        $user = $this->userRepository->find(1);

        $this->client->loginUser($user);

        $task = $this->taskRepository->findOneBy(["title" => "nouveau titre"]);

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_delete", ["id" => $task->getId()]));

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}