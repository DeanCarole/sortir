<?php

namespace App\Services;


use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;

class Update
{
    private EventRepository $eventRepository;
    private StateRepository $stateRepository;

    private EntityManagerInterface $entityManager;
    public function __construct(EventRepository $eventRepository, StateRepository $stateRepository, EntityManagerInterface $entityManager)
    {
        $this->stateRepository = $stateRepository;
        $this->eventRepository = $eventRepository;
        $this->entityManager = $entityManager;
    }


    public function updateState() {
    //modifier statut d'un évènement suivant la date de début de la sortie

    //récupère toutes les sorties avec le repository
    $events = $this->eventRepository->findAllEvents();

    $states = $this->stateRepository->findAll();

    //boucle sur chaque évènement
    foreach ($events as $event) {

        foreach ($states as $state){

        //récupère le statut de l'évènement
        $status = $event->getState()->getLabel();
        
       $event1 = clone $event->getStartDateTime();
       $event1->modify("+" .$event->getDuration() . "minute");

//       if($event->getId() == 1){
//           dump($event);
//           dump($event1);
//       }

        //condition pour modifier l'évènement
        //cas où ça date de plus de 1 mois
        if ($event1 < new \DateTime('-1 month') ) {
            $event->setState($state->setLabel('archived'));
            //entre 1 mois et aujourd'hui
        } else  if ($event1 < new \DateTime())  {
            $event->setState($state->setLabel('finished'));
        //} else if ((new \DateTime('now') >= $event->getStartDateTime()) &&  ( $event->getStartDateTime() < $event1 )) {
        } else if ($event->getStartDateTime() < new \DateTime()) {
            $event->setState($state->setLabel('inProgress'));
        } else if ((new \DateTime() > $event->getRegistrationDeadline())){
            $event->setState($state->setLabel('closed'));
        }

//        if($event->getId() == 9){
//            dump($event);
//        }

        //permet de faire le persist et le flush -> modifie la base de données
       $this->entityManager->persist($event);

        }
    }
        $this->entityManager->flush($events);



}
}