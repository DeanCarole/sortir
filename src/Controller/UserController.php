<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/show/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        return $this->render('user/show.html.twig');
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(int $id): Response
    {
        return $this->render('user/update.html.twig');
    }
}
