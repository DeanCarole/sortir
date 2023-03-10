<?php

namespace App\Controller;


use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\Uploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function update(Request $request,
                           UserRepository $userRepository,
                           UserPasswordHasherInterface $passwordHasher,
                           Uploader $uploader): Response
    {
        $user = $this->getUser();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // récupérer les données du formulaire
            $user = $userForm->getData();

            // Modifier le mot de passe si le champ est rempli
            $password = $userForm->get('password')->getData();
            if ($password) {
                //$user = new User();
                $newPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($newPassword);
            }

            //Gestion de l'upload de photo d'une série nouvellement créée
            /**
             * @var UploadedFile $file
             */
            $file = $userForm->get('picture')->getData();

            //Appel de la méthode upload de notre service Uploader
            $newFileName = $uploader->upload(
                $file,
                $this->getParameter('upload_user_picture'),
                $user->getUserIdentifier());

            //Sette le nouveau nom du
            $user->setPicture($newFileName);

            $userRepository->save($user,true);
            $this->addFlash('success',"Profil modifié !");

            // rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('main_home');
        }



        return $this->render('user/update.html.twig',['user' => $user,
            'userForm' => $userForm->createView()
        ]);
    }

}
