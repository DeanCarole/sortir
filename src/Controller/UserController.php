<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/show', name: 'show')]
    public function show(int $id): Response
    {
        return $this->render('user/show.html.twig');
    }
 //Pour afficher l'utilisateur modifiable
//    #[Route('/update', name: 'update')]
//    public function update(Request $request,UserRepository $userRepository): Response
//    {
//        $user = $this->getUser();
//        $userForm = $this->createForm(UserType::class, $user);
//
//        return $this->render('user/update.html.twig',['user' => $user,
//            'userForm' => $userForm->createView()
//        ]);
//    }

    //Pour modifier l'utilisateur dans la BDD
    #[Route('/update', name: 'update')]
    public function update(Request $request,UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // récupérer les données du formulaire
            $user = $userForm->getData();

            // mettre à jour les champs modifiables de l'utilisateur
            $user->setUsername($user->getUsername());
            $user->setName($user->getName());
            $user->setFirstName($user->getFirstName());
            $user->setPhone($user->getPhone());
            $user->setEmail($user->getEmail());
            $user->setPassword($user->getPassword());
            $userRepository->save($user,true);
            $this->addFlash('success',"Profil Update !");

            // rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('main_home');
        }

        return $this->render('user/update.html.twig',['user' => $user,
            'userForm' => $userForm->createView()
        ]);
    }

}
