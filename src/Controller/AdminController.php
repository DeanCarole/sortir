<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard', name: 'dashboard_')]
class AdminController extends AbstractController
{
    #[Route('/city', name: 'city')]
    public function setCities(): Response
    {
        return $this->render('admin/adminCities.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/campus', name: 'campus')]
    public function setCampuses(CampusRepository $campusRepository, Request $request): Response
    {

        //affiche tous les campus
        $campuses = $campusRepository->findAll();

        //je récupère le formulaire
      $campusForm = $this->createForm(CampusType::class);

     $campusForm->handleRequest($request);


        if ($campusForm->isSubmitted()) {

            //je récupère les données du formulaire
            $campus = $campusForm->getData();

            $campusRepository->save($campus, true);
            $this->addFlash('success', "Campus créé !");
        }

        return $this->render('admin/adminCampuses.html.twig', [
            'campuses' => $campuses, 'campusForm' => $campusForm->createView()]);
    }

//    #[Route('/campus/add', name: 'campusAdd')]
//    public function campusAdd(CampusRepository $campusRepository, Request $request): Response
//    {
//dd('coucou');
//ajout campus à la BDD
//        $saisie = $request->query->get('saisie');
//
//        $campus = new Campus();
//
//        $campusForm = $this->createForm(CampusType::class, $campus);
//        $campusForm->handleRequest($request);
//
//        $campus = $campusForm->getData();
//
//        $campusRepository->save($campus,true);
//        $this->addFlash('success',"Campus créé !");
//
//        return $this->redirectToRoute('dashboard_campus', [
//            'campusForm' => $campusForm->createView()]);
//    }


}



