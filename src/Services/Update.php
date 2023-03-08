<?php

namespace App\Services;

use App\Entity\State;
use App\Repository\EventRepository;

class Update
{




public function updateState(EventRepository $eventRepository) {
    //modifier statut d'un évènement suivant la date de début de la sortie

    //récupère toutes les sorties avec le repository
    $events = $eventRepository->findAll();

    //boucle sur chaque évènement
    foreach ($events as $event) {
        //récupère le statut de l'évènement
        $status = $event->getState()->getLabel();
        //condition pour modifier l'évènement
        if ($event->getStartDateTime() < new \DateTime('-1 month') ) {
            $state = new State();
            $event->setState($state->setLabel('archived'));
        }
        if ($event->getStartDateTime() == new \DateTime('now') ) {
            $state = new State();
            $event->setState($state->setLabel('inProgress'));
        }
        if (($event->getStartDateTime() < new \DateTime()) && ($event->getStartDateTime() > new \DateTime('-1 month')))  {
            $state = new State();
            $event->setState($state->setLabel('finished'));
        }


    }

}





}