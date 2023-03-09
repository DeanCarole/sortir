<?php

namespace App\Services;


use App\Repository\EventRepository;
use App\Repository\StateRepository;

class Update
{
    private EventRepository $eventRepository;
    private StateRepository $stateRepository;
    public function __construct(EventRepository $eventRepository, StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
        $this->eventRepository = $eventRepository;
    }


    public function updateState() {
    //modifier statut d'un évènement suivant la date de début de la sortie

    //récupère toutes les sorties avec le repository
    $events = $this->eventRepository->findAll();

    //boucle sur chaque évènement
    foreach ($events as $event) {
        //récupère le statut de l'évènement
        $status = $event->getState()->getLabel();
        
       $event1 = clone $event->getStartDateTime();
       $event1->modify("+" .$event->getDuration() . "minute");

        //condition pour modifier l'évènement
        if ($event->getStartDateTime() < new \DateTime('-1 month') ) {
            $state = $this->stateRepository->findOneBy(['label'=>'archived']);
            $event->setState($state);
        } else  if (($event->getStartDateTime() < new \DateTime()) && ($event->getStartDateTime() > new \DateTime('-1 month')))  {
            $state = $this->stateRepository->findOneBy(['label'=>'finished']);
            $event->setState($state);
        //} else if ((new \DateTime('now') >= $event->getStartDateTime()) &&  ( $event->getStartDateTime() < $event1 )) {
        } else if (($event->getStartDateTime() >= new \DateTime()) && ($event->getStartDateTime()< $event1 )) {
            $state = $this->stateRepository->findOneBy(['label'=>'inProgress']);
            $event->setState($state);
        } else if ((new \DateTime() > $event->getRegistrationDeadline())){
            $state = $this->stateRepository->findOneBy(['label'=>'closed']);
            $event->setState($state);
        }

        //permet de faire le persist et le flush -> modifie la base de données
        $this->eventRepository->save($event, true);
    }

}





}