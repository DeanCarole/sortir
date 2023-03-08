<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
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
 //Pour modifier l'utilisateur
    #[Route('/update', name: 'update')]
    public function update(UserRepository $userRepository): Response
    {
        // récupérer l'utilisateur' et le renvoyer via l'id
       // $user = $userRepository->find($id);
        $user = $this->getUser();

        $userForm = $this->createForm(UserType::class, $user);

        return $this->render('user/update.html.twig',['user' => $user,
            'userForm' => $userForm->createView()
        ]);
    }
}
