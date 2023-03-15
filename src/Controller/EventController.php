<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\State;
use App\Form\CancelEventType;
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


    #[Route('/publish{id}', name: 'publish', requirements: ['id' => '\d+'])]
    public function publish(int $id, EventRepository $eventRepository, StateRepository $stateRepository): Response
    {
        //Récupération d'un event par son id
       $event = $eventRepository->find($id);
       $stateCreated = $stateRepository->findOneBy(['label' => 'created']);

       // mettre des conditions pour modifier l'état de la sortie
        //conditions = état created

        //récupère l'état de la sortie
        $state = $event->getState();
        if ($state === $stateCreated) {

            //modifier l'état de la sortie
            $state = $stateRepository->findOneBy(['label' => 'open']);
            $event->setState($state);
            $eventRepository->save($event, true);
            $this->addFlash('success', "Etat modifié : Sortie publiée !");
        } else {
            //throw $this->createNotFoundException("Oups ! Tu ne peux plus publier cette sortie !");
            $this->addFlash('error', "Sortie non publiable, n'a pas le bon état !");
        }


        return $this->redirectToRoute('main_home');
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
    public function deleteEvent(int $id, EventRepository $eventRepository, Request $request, StateRepository $stateRepository): Response
    {
        //Récupération d'un event par son id
        $event = $eventRepository->find($id);

        //Stockage de la description d'origine
        $eventDataInit = $event->getEventData();

        //Récupération d'un état de type closed
        $stateCanceled = $stateRepository->findOneBy(['label' => 'canceled']);

        //Génération du formulaire & lecture de la requête HTTP
        $eventForm = $this->createForm(CancelEventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            //Append du message d'annulation en complément de la description
            $event->setEventData($eventDataInit . ' ANNULE : ' . $eventForm->get('eventData')->getData());

            //Passage à l'état annulé
            $event->setState($stateCanceled);

            //Sauvegarde en base de donnée + décelenchement du message flash
            $eventRepository->save($event,true);
            $this->addFlash('success',"Sortie annulée !");

            //Rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('main_home');
        }

        return $this->render('event/deleteEvent.html.twig', [
            'event' => $event, 'cancelEventForm' => $eventForm->createView()
        ]);
    }




}
