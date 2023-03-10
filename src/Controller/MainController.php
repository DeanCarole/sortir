<?php

namespace App\Controller;


use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Services\Update;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function home(EventRepository $eventRepository, Update $update): Response
    {
        $user = $this->getUser();

        $events = $eventRepository->findAll();
        $update->updateState();




        return $this->render('main/home.html.twig', ['events'=>$events ,'user'=>$user]);
    }


    #[Route('/home/{id}', name: 'main_addUserEvent', requirements: ['id' => '\d+'])]
    public function addUserEvent(int $id, EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();
        $user->addEvent($event);
        $event->addUser($user);

        $userRepository->save($user,true);
        $eventRepository->save($event, true);



        return $this->render('event/show.html.twig', [
            'user' => $user, 'event' => $event
        ]);
    }
}

//        $entityManager->persist($user);
//        $entityManager->persist($event);
//        $entityManager->flush();