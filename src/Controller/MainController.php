<?php

namespace App\Controller;


use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Services\Update;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function home(EventRepository $eventRepository, Update $update): Response
    {
        $user = $this->getUser();
        //$events = $eventRepository->findAll();
        $events = $eventRepository->findAll();
        $update->updateState();




        return $this->render('main/home.html.twig', ['events'=>$events ,'user'=>$user]);
    }

    #[Route('/home', name: 'main_addUserEvent')]
    public function addUserEvent(int $id, EventRepository $eventRepository): Response
    {
        $user = $this->getUser();
        $event = $eventRepository->find($id);
        if(!$user){
            throw $this->createNotFoundException("Oops ! User not found !");
        }

        return $this->render('event/show.html.twig', [
            'user' => $user
        ]);
    }


}
