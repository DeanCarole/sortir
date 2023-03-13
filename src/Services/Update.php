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
    $events = $this->eventRepository->findAllEvents();

    //boucle sur chaque évènement
    foreach ($events as $event) {
        //récupère le statut de l'évènement
        $status = $event->getState()->getLabel();
        
       $event1 = clone $event->getStartDateTime();
       $event1->modify("+" .$event->getDuration() . "minute");

       if($event->getId() == 1){
           dump($event);
           dump($event1);
       }

        //condition pour modifier l'évènement
        //cas où ça date de plus de 1 mois
        if ($event1 < new \DateTime('-1 month') ) {

            $state = $this->stateRepository->findOneBy(['label'=>'archived']);
            $event->setState($state);
            //entre 1 mois et aujourd'hui
        } else  if ($event1 < new \DateTime())  {

            $state = $this->stateRepository->findOneBy(['label'=>'finished']);
            $event->setState($state);
        //} else if ((new \DateTime('now') >= $event->getStartDateTime()) &&  ( $event->getStartDateTime() < $event1 )) {
        } else if ($event->getStartDateTime() < new \DateTime()) {
            dump($event);
            $state = $this->stateRepository->findOneBy(['label'=>'inProgress']);
            $event->setState($state);
        } else if ((new \DateTime() > $event->getRegistrationDeadline())){

            $state = $this->stateRepository->findOneBy(['label'=>'closed']);
            $event->setState($state);
        }

//        if($event->getId() == 9){
//            dump($event);
//        }

        //permet de faire le persist et le flush -> modifie la base de données
        $this->eventRepository->save($event, true);
    }

}





}