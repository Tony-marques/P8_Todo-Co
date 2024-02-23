<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;
    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->container = static::getContainer();

        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->taskRepository = $this->entityManager->getRepository(Task::class);

        $this->userRepository = $this->entityManager->getRepository(User::class);

        $this->urlGenerator = $this->container->get(UrlGeneratorInterface::class);

        $this->loadFixtures();
    }

    private function loadFixtures(): void
    {
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $databaseTool->loadFixtures([
            AppFixtures::class
        ]);
    }


    public function testUserListPageWithRoleAdmin()
    {
        $user = $this->userRepository->findOneBy(["username" => "admin"]);

        $this->client->loginUser($user);

        $this->client->request(Request::METHOD_GET, "/users");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateUser()
    {
        $userAdmin = $this->userRepository->findOneBy(["username" => "admin"]);

        $this->client->loginUser($userAdmin);

        $crawler = $this->client->request(Request::METHOD_GET, "/users/create");

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user3',
            'user[password][first]' => '12345',
            'user[password][second]' => '12345',
            'user[email]' => 'user3@gmail.com',
            'user[roles]' => 'ROLE_USER'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', "L'utilisateur a bien été ajouté.");
    }

    public function testEditUser()
    {
        $userAdmin = $this->userRepository->findOneBy(["username" => "admin"]);

        $this->client->loginUser($userAdmin);

        $user = $this->userRepository->findOneBy(["username" => "user1"]);

        $url = $this->urlGenerator->generate("user_edit", ["id" => $user->getId()]);

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $form = $crawler->selectButton("Modifier")->form([
            'user[username]' => 'user3 modifié',
            'user[password][first]' => '12345',
            'user[password][second]' => '12345',
            'user[email]' => 'user3modifie@gmail.com',
            'user[roles]' => 'ROLE_USER'
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', "L'utilisateur a bien été modifié");
    }

}