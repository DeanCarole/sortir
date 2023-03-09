<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add( Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            // récupérer les données du formulaire
            $event = $eventForm->getData();

            // Modifier le mot de passe si le champ est rempli
            $password = $eventForm->get('password')->getData();


            $eventRepository->save($event, true);
            $this->addFlash('success', "Profil modifié !");


        }

        return $this->render('event/add.html.twig', [
            'eventForm' => $eventForm->createView()
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, EventRepository $eventRepository): Response
    {
        //Récupération d'un event par son id
        $event = $eventRepository->find($id);

        if(!$event){
            //Lance une erreur 404 si l'utilisateur n'existe pas
            throw $this->createNotFoundException("Oups ! Cette sortie n'existe pas !");
        }

        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }





}
