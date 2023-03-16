<?php

namespace App\Controller;


use App\Form\Filter\Filter;
use App\Form\FilterType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use App\Services\Update;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function home(EventRepository $eventRepository, Update $update, Request $request): Response
    {
        $user = $this->getUser();
        // $update->updateState();

        //Liste l'ensemble des sorties
        $filter = new Filter();

        //Création fomrulaire des filtres
        $filterForm = $this->createForm(FilterType::class, $filter);
        $filterForm->handleRequest($request);
        $events = $eventRepository->findAllEventsFilter($filter, $user);

        return $this->render('main/home.html.twig', [
            'events' => $events,
            'user' => $user,
            'filterForm' => $filterForm->createView()]);
    }

//    #[Route('/home/{id}', name: 'main_addUserEvent', requirements: ['id' => '\d+'])]
//    public function addUserEvent(int $id, EventRepository $eventRepository, UserRepository $userRepository): Response
//    {
//        $event = $eventRepository->find($id);
//        $user = $this->getUser();
//
//        // verifie si deja inscrit
//        if ($user->isRegister($event)) {
//            $user->removeEvent($event);
//            $event->removeUser($user);
//        } else {
//            // Si pas encore inscrit
//            $user->addEvent($event);
//            $users = $event->getUser();
//            $event->addUser($user);
//        }
//
//        $userRepository->save($user, true);
//        $eventRepository->save($event, true);
//
//        return $this->render('event/show.html.twig', [
//            'users' => $event->getUser(), 'event' => $event
//        ]);
//    }
    #[Route('/home/{id}', name: 'main_addUserEvent', requirements: ['id' => '\d+'])]
    public function addUserEvent(int $id, StateRepository $stateRepository, EventRepository $eventRepository, UserRepository $userRepository, Update $update): Response
    {
        //je récupère mon tableau d'état
        $states = $update->tableState();

        $event = $eventRepository->find($id);
        $user = $this->getUser();
        $stateClosed = $states['closed'];
        $stateOpen = $states['open'];

        // Vérifie si déjà inscrit on le le désinscrit
        if ($user->isRegister($event)) {
            $user->removeEvent($event);
            $event->removeUser($user);
            $event->setState($stateOpen);
            $this->addFlash('success',"Désinscrit à la sortie !");
        } else {
            // Si pas encore inscrit, vérifie le nombre maximal d'inscriptions
            $maxRegistrations = $event->getNbRegistrationMax();
            if ($maxRegistrations && count($event->getUser()) >= $maxRegistrations) {
                // Le nombre maximal d'inscriptions a été atteint
                $this->addFlash('error',"Sortie complète !");
           } elseif ( count($event->getUser()) == $maxRegistrations -1){
                $user->addEvent($event);
                $event->addUser($user);
                $event->setState($stateClosed);
                $this->addFlash('success',"Inscrit à la sortie !");
            } else {
                $user->addEvent($event);
                $event->addUser($user);
                $event->setState($stateOpen);
                $this->addFlash('success',"Inscrit à la sortie !");
            }
        }

        $userRepository->save($user, true);
        $eventRepository->save($event, true);

        return $this->render('event/show.html.twig', [
            'users' => $event->getUser(), 'event' => $event
        ]);
    }

}

