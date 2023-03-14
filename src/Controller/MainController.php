<?php

namespace App\Controller;



use App\Form\Filter\Filter;
use App\Form\FilterType;
use App\Repository\EventRepository;
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
        $update->updateState();

        //Liste l'ensemble des sorties

        $filtre = new Filter();

        //Création fomrulaire des filtres
        $filterForm = $this->createForm(FilterType::class, $filtre);
        $filterForm->handleRequest($request);
        $events = $eventRepository->findAllEventsFilter($filtre, $user);



        return $this->render('main/home.html.twig', [
            'events'=>$events ,
            'user'=>$user,
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
   public function addUserEvent(int $id, EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();

        // Vérifie si déjà inscrit
        if ($user->isRegister($event)) {
            $user->removeEvent($event);
            $event->removeUser($user);
        } else {
            // Si pas encore inscrit, vérifie le nombre maximal d'inscriptions
            $maxRegistrations = $event->getNbRegistrationMax();
            if ($maxRegistrations && count($event->getUser()) >= $maxRegistrations) {
                // Le nombre maximal d'inscriptions a été atteint
                $this->addFlash('success',"Sortie complète !");
            } else {
                $user->addEvent($event);
                $users = $event->getUser();
                $event->addUser($user);
            }
        }

        $userRepository->save($user, true);
        $eventRepository->save($event, true);

        return $this->render('event/show.html.twig', [
            'users' => $event->getUser(), 'event' => $event
        ]);
    }

}

