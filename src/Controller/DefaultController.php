<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route(path: "/", name: "homepage")]
    public function indexAction(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
