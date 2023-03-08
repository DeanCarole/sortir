<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    public function update(Request $request,UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
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

            // Modifier le mot de passe si le champ est rempli
            $password = $userForm->get('password')->getData();
            if ($password) {
                //$user = new User();
                $newPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($newPassword);
            }


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
