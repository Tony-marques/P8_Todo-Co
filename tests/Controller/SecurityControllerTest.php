<?php

namespace App\tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp():void{
        $this->client = static::createClient();
    }

    public function testSuccessLogin() {
        $crawler = $this->client->request(Request::METHOD_GET, "/login");

        $form = $crawler->selectButton("Se connecter")->form([
            "_username" => "admin",
            "_password" => "12345"
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);


        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

    }
}