<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function index(): Response
    {
        return $this->render('event/add.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, EventRepository $eventRepository): Response
    {
        //Récupération d'un event par son id
        $event = $eventRepository->find($id);

        if(!$event){
            //Lance une erreur 404 si l'event n'existe pas
            throw $this->createNotFoundException("Oups ! Cette sortie n'existe pas !");
        }

        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }





}
