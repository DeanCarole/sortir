<?php

namespace App\Services;


use App\Repository\EventRepository;
use App\Repository\StateRepository;
use DateTime;
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


    //fonction qui récupère tous les états de la BDD
    public function tableState (){

        $states = [] ;
        foreach ($this->stateRepository->findAll() as $state){
            $i = $state->getLabel();
            $states [$i] = $state;
        }
        return $states;
    }

    public function updateState($states): void
    {
    //modifier statut d'un évènement suivant la date de début de la sortie

    //récupère toutes les sorties avec le repository
    $events = $this->eventRepository->findAllEventsUpdate();

    //boucle sur chaque évènement
    foreach ($events as $event) {

        //récupère le statut de l'évènement
        $status = $event->getState()->getLabel();

        //cette variable stocke la fin de l'évènement (date + durée)
       $event1 = clone $event->getStartDateTime();
       $event1->modify("+" .$event->getDuration() . "minute");


        //condition pour modifier l'évènement
        //cas où ça date de plus de 1 mois
        if ($event1 < new DateTime('-1 month') ) {

             $state = $states['archived'];
            //$state = $this->stateRepository->findOneBy(['label' => 'archived']);
            $event->setState($state);
            //entre 1 mois et aujourd'hui
        } else  if ($event1 < new DateTime())  {
            $state = $states['finished'];
           // $state = $this->stateRepository->findOneBy(['label' => 'finished']);
            $event->setState($state);
        //} else if ((new \DateTime('now') >= $event->getStartDateTime()) &&  ( $event->getStartDateTime() < $event1 )) {
        } else if ($event->getStartDateTime() < new DateTime()) {
            $state = $states['inProgress'];
           // $state = $this->stateRepository->findOneBy(['label' => 'inProgress']);
            $event->setState($state);
        } else if ((new DateTime() > $event->getRegistrationDeadline())){
            $state = $states['closed'];
            //$state = $this->stateRepository->findOneBy(['label' => 'closed']);
            $event->setState($state);
        }
           // $this->eventRepository->save($event, true);
        //permet de faire le persist et le flush -> modifie la base de données

        //$this->eventRepository->save($event, true);
        $this->entityManager->persist($event);
        }
$this->entityManager->flush($events);

}


}