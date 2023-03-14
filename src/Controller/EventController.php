<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\PlaceRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request, EventRepository $eventRepository, PlaceRepository $placeRepository, StateRepository $stateRepository): Response
    {
        //crée un évènement vide
        $event = new Event();
        //je crée un objet état avec pour label created
        $state = $stateRepository->findOneBy(['label'=>'created']);
        //je set l'état created à l'évènement
        $event->setState($state);

        $user = $this->getUser();
        $event->setPlanner($user);
        $event->addUser($user);

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            // récupérer les données du formulaire
            $event = $eventForm->getData();

            $eventRepository->save($event, true);
            $this->addFlash('success', "Sortie créée !");
        }

        $places = $placeRepository->findAll();

        return $this->render('event/add.html.twig', [
            'eventForm' => $eventForm->createView(),
            'places' => $places
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

    #[Route('/{id}/update', name: 'updateEvent', requirements: ['id' => '\d+'])]
    public function updateEvent(int $id, EventRepository $eventRepository, Request $request): Response
    {
        //Récupération d'un event par son id
        $event = $eventRepository->find($id);

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $event = $eventForm->getData();

            $eventRepository->save($event,true);
            $this->addFlash('success',"Sortie modifiée !");

            // rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('main_home');
        }

        return $this->render('event/updateEvent.html.twig', [
            'event' => $event, 'eventForm' => $eventForm->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'deleteEvent', requirements: ['id' => '\d+'])]
    public function deleteEvent(int $id, EventRepository $eventRepository, Request $request): Response
    {
        //Récupération d'un event par son id
        $event = $eventRepository->find($id);

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $event = $eventForm->getData();
            $eventData = $eventForm->get('eventData')->getData();
            if ($eventData) {

                $event->setEventData();
            }

            $eventRepository->save($event,true);
            $this->addFlash('success',"Sortie annulée !");

            // rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('main_home');
        }

        return $this->render('event/deleteEvent.html.twig', [
            'event' => $event, 'eventForm' => $eventForm->createView()
        ]);
    }




}
